<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyRequest;
use App\Http\Requests\JobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobDetailResource;
use App\Http\Resources\JobListResource;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Notifications\NewJobApplication;
use App\Services\FileUploadService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\json;

class JobController extends Controller
{
    use HttpResponses, Notifiable;
    public function index(Request $request)
    {
        $jobs = Job::with('company')
            ->filter($request->all())
            ->latest()
            ->paginate(10);
        $formattedJobs = JobListResource::collection($jobs);
        return $this->success($formattedJobs, 'Jobs retrieved successfully', 200);
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
        $user = Auth::user();
        $company = Company::where('user_id', $user->id);
        if (!$company) {
            return $this->error(null, 'You must have a company profile to post a job', 403);
        }
        $validated = $request->validated();
        $validated['company_id'] = $company->id;

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

        $application = Application::where('applicant_id', $user->applicant->id)
            ->where('job_id', $jobId)
            ->first();

        DB::beginTransaction();
        $newCvPath = null;

        try {
            if ($request->hasFile('cv')) {
                $newCvPath = $fileService->upload($request->file('cv'), 'cvs', 'local');
            }

            if ($application) {
                if ($application->status !== 'pending') {
                    if ($newCvPath) $fileService->delete($newCvPath, 'local');
                    return $this->error(null, 'Cannot update application after it has been reviewed', 400);
                }

                if ($application->cv) {
                    $fileService->delete($application->cv, 'local');
                }

                $application->update([
                    'cv' => $newCvPath,
                    'notes' => $request->input('notes', $application->notes),
                ]);

                $message = 'Application CV updated successfully';
            } else {

                Application::create([
                    'applicant_id' => $user->applicant->id,
                    'job_id'       => $job->id,
                    'status'       => 'pending',
                    'cv'           => $newCvPath,
                    'notes'        => $request->input('notes'),
                ]);

                $job->company->notify(new NewJobApplication(
                    $job->title,
                    $user->applicant->full_name,
                    $job->id
                ));

                $message = 'Application submitted successfully';
            }

            DB::commit();
            return $this->success(null, $message, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($newCvPath) {
                $fileService->delete($newCvPath, 'local');
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
