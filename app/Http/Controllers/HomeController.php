<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Traits\HttpResponses;

class HomeController extends Controller
{
    use HttpResponses;
    public function index()
    {
        $jobs = Job::latest()->take(10)->get();

        return $this->success($jobs, 'Latest jobs retrieved successfully', 200);
    }
}
