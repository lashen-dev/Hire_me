<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyRequest;
use App\Http\Requests\JobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobDetailResource;
use App\Http\Resources\JobListResource;
use App\Models\Application;
use App\Models\Job;
use App\Notifications\NewJobApplication;
use App\Services\FileUploadService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\json;

class JobController extends Controller
{
    use HttpResponses , Notifiable;
    public function index()
    {

        return Cache::remember('jobs', 350, function () {
            $jobs = Job::all();
            $formattedJobs = JobListResource::collection($jobs)->resolve();
            return $this->success($formattedJobs, 'Jobs retrieved successfully', 200);
        });
    }


    public function show($id)
    {
        // Return a single job
        $job = Job::findOrFail($id);
        return $this->success(new JobDetailResource($job), 'Job retrieved successfully', 200);
    }

    public function store(JobRequest $request)
    {
        // Create a new job
        $validated = $request->validated();

        $job = Job::create($validated);
        return $this->success($job, 'Job created successfully', 201);
    }

    public function update(UpdateJobRequest $request, $id)
    {
        // Update a job


        $job = Job::findOrFail($id);
        $validated = $request->validated();
        $job->update($validated);
        return $this->success($job, 'Job updated successfully', 200);
    }

    public function destroy($id)
    {
        // Delete a job
        $job = Job::findOrFail($id);
        $job->delete();
        return $this->success(null, 'Job deleted successfully', 200);
    }




    public function apply(ApplyRequest $request, $jobId, FileUploadService $fileService)
    {
        $user = $request->user();
        $job = Job::findOrFail($jobId);

        $exists = Application::where('applicant_id', $user->applicant->id)
            ->where('job_id', $jobId)
            ->exists();

        if ($exists) {
            return $this->success(null, 'You have already applied for this job', 409);
        }

        DB::beginTransaction();
        $cvPath = null;

        try {

            if ($request->hasFile('cv')) {
                $cvPath = $fileService->upload($request->file('cv'), 'cvs', 'local');
            }

            Application::create([
                'applicant_id' => $user->applicant->id,
                'job_id'       => $job->id,
                'status'       => 'pending',
                'cv'           => $cvPath,
            ]);

            $job->company->notify(new NewJobApplication(
                $job->title,
                $user->applicant->full_name,
                $job->id
            ));

            DB::commit();

            return $this->success(null, 'Application submitted successfully', 201);

        } catch (\Exception $e) {

            DB::rollBack();
            if ($cvPath) {
                $fileService->delete($cvPath, 'local');
            }

            return $this->error(null, 'Something went wrong', 500);
        }
    }

    public function getApplicants($jobId)
    {
        // Get applicants for a job
        $job = Job::findOrFail($jobId);
        $applicants = $job->applicants;
        if (count($applicants) >= 1) {
            return $this->success($applicants, 'Applicants retrieved successfully', 200);
        }
        return $this->error(null, 'No applicants found for this job', 404);
    }
}
