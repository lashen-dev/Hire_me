<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use HttpResponses;
     public function search(Request $request)
    {
        $job = Job::with('company')
        ->filter($request->only(['title', 'location', 'salary']))
        ->paginate(10);
        return $this->success($job, 'Job search results retrieved successfully.');
    }

}
