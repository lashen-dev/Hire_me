<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Company;
use App\Models\Job;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    use HttpResponses;
    public function getCompanies()
    {
        $Companies = Company::select('id', 'name')->get();
        return $this->success($Companies, 'Companies retrieved successfully', 200);
    }
    public function getApplicants()
    {
        $Applicants = Applicant::select('id', 'name')->get();
        return $this->success($Applicants, 'Applicants retrieved successfully', 200);
    }

    public function destroyCompany($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return $this->success(null, 'Company deleted successfully', 200);
    }
    public function destroyApplicant($id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->delete();
        return $this->success(null, 'Applicant deleted successfully', 200);
    }
    public function destroyJob($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();
        return $this->success(null, 'Job deleted successfully', 200);
    }



}
