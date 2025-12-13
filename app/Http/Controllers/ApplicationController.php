<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Notifications\ApplicantStatus;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    use HttpResponses , Notifiable;
    public function index()
    {
        // Return a list of applications
        $applications = Application::all();
        return $this->success($applications, 'Applications retrieved successfully', 200);
    }

    public function show($id)
    {
        // Return a single application
        $application = Application::findOrFail($id);
        return $this->success($application, 'Application retrieved successfully', 200);
    }

    public function update(ApplicationRequest $request, $id)
    {
        $user = Auth::user();
        $application = Application::with('job')->findOrFail($id);
        $company = Company::where('user_id', $user->id)->first();
        
        if ($application->job->company_id !== $company->id) {
            return $this->error(null, 'Unauthorized to update this application', 403);
        }
        $validated = $request->validated();

        $application_updated = $application->update($validated);

        if (!$application_updated) {
            return $this->error(null, 'Failed to update application status', 500);
        }

        if ($validated['status'] === 'accepted') {
            Applicant::where('id', $application->applicant_id)->update([
                'company_id' => $application->job->company_id
            ]);
            
        }
        $status = $validated['status'];
        $application->applicant->user->notify(new ApplicantStatus($status, $application->job->title));

        return $this->success( null , 'Application status updated successfully', 200);
    }

    public function destroy($id)
    {
        // Delete an application
        $application = Application::findOrFail($id);
        $application->delete();
        return $this->success(null, 'Application deleted successfully', 200);
    }
}
