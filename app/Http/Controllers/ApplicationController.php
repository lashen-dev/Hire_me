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
    use HttpResponses, Notifiable;
    public function index()
    {
        $user = Auth::user();

        $query = Application::with(['job', 'applicant']);

        if ($user->role === 'applicant') {
            $query->whereHas('applicant', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($user->role === 'company') {
            $query->whereHas('job', function ($q) use ($user) {
                $q->where('company_id', $user->company->id);
            });
        }

        $applications = $query->latest()->paginate(10);

        return $this->success($applications, 'Applications retrieved successfully', 200);
    }

    public function show($id)
    {
        $user = Auth::user();

        $application = Application::with(['job', 'applicant'])->findOrFail($id);

        $isOwner = $application->applicant->user_id === $user->id;

        $isCompanyOwner = false;
        if ($user->role === 'company') {
            $isCompanyOwner = $application->job->company_id === $user->company->id;
        }

        $isAdmin = $user->hasRole('admin') || $user->hasPermissionTo('view-application');

        if (!$isOwner && !$isCompanyOwner && !$isAdmin) {
            return $this->error(null, 'Unauthorized access', 403);
        }

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

        return $this->success(null, 'Application status updated successfully', 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $application = Application::findOrFail($id);

        $isApplicantOwner = $application->applicant->user_id === $user->id;

        $isAdmin = $user->hasRole('admin') || $user->hasPermissionTo('delete-application');

        if ($isApplicantOwner) {
            if ($application->status !== 'pending') {
                return $this->error(null, 'Cannot withdraw application after it has been reviewed', 400);
            }

            $application->delete();
            return $this->success(null, 'Application withdrawn successfully', 200);
        }

        if ($isAdmin) {
            $application->delete();
            return $this->success(null, 'Application deleted by admin', 200);
        }

        return $this->error(null, 'Unauthorized', 403);
    }
}
