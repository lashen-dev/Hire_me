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
        $Job = Job::query()
            ->when($request->q, function ($query) use ($request) {
                $query->where(
                    fn($query) =>
                    $query->where('title', 'like', "%{$request->q}%")
                );
            })->get();

        return $this->success($Job, 'Search results retrieved successfully', 200);
    }

}
