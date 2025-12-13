<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Requests\JobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Company;
use App\Models\Job;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    use HttpResponses;
    public function index()
    {
        // Return a list of companies
        $all_company = Company::all();
        if ($all_company->isEmpty()) {
            return $this->error(null, 'No companies found', 404);
        }

       

        return $this->success($all_company, 'Companies retrieved successfully', 200);
    }

    public function show($id)
    {
        // Return a single company
        $company = Company::findorFail($id);
        if (!$company) {
            return $this->error(null, 'Company not found', 404);
        }
        $company->profile;
        return $this->success($company, 'Company retrieved successfully', 200);
    
    }

    public function update(CompanyRequest $request)
    {
        $user = Auth::user();
        
        $company = Company::where('user_id', $user->id)->first();

        if ($request->has('name')) $company->name = $request->name;
        if ($request->has('logo')) $company->logo = $request->logo;
        if ($request->has('website')) $company->website = $request->website;
        if ($request->has('address')) $company->address = $request->address;
        if ($request->has('phone')) $company->phone = $request->phone;
        if ($request->has('description')) $company->description = $request->description;
        
        $company->save();


    

        return $this->success($company, 'Company updated successfully', 200);
    }

    

    public function destroy($id)
    {
        $company = Company::find($id);

        if ($company) {
        $company->delete(); 
        } else {
            return $this->error(null, 'Company not found', 404);
        }
       
        return $this->success(null, 'Company deleted successfully', 200);
    }

    public function addJob(JobRequest $request, $id)
    {
        // Add a new job to a company
        $company = Company::findorFail($id);
        if (!$company) {
            return $this->error(null, 'Company not found', 404);
        }
        $validated = $request->validated();
        $validated['company_id'] = $company->id;
        $validated['company_logo'] = $company->logo;

        $jobExists = Job::where('title', $validated['title'])
            ->where('company_id', $company->id)
            ->where('type', $validated['type'])
            ->first();

        if ($jobExists) {   
            return $this->error(null, 'Job with the same title and type already exists for this company', 409);
        }


        $new_job = Job::create($validated);
        return $this->success($new_job, 'Job created successfully', 201);
        
    }
    public function getJobs($id)
    {
        // Get all jobs for a company
        $company = Company::findorFail($id);
        if (!$company) {
            return $this->error(null, 'Company not found', 404);
        }
        $jobs = $company->jobs;
        if ($jobs->isEmpty()) {
            return $this->error(null, 'No jobs found for this company', 404);
        }
        return $this->success($jobs, 'Jobs retrieved successfully', 200);
    }
    public function getApplicants($id)
    {
        // Get all applicants for a company
        $company = Company::findorFail($id);
        if (!$company) {
            return $this->error(null, 'Company not found', 404);
        }
        $applicants = $company->applicants;
        if ($applicants->isEmpty()) {
            return $this->error(null, 'No applicants found for this company', 404);
        }
        return $this->success($applicants, 'Applicants retrieved successfully', 200);
    }

    public function getApplications($id)
    {
        // Get all applications for a company
        $company = Company::findorFail($id);
        if (!$company) {
            return $this->error(null, 'Company not found', 404);
        }
        $applications = $company->applications;
        if ($applications->isEmpty()) {
            return $this->error(null, 'No applications found for this company', 404);
        }
        return $this->success($applications, 'Applications retrieved successfully', 200);
    }


 
}
