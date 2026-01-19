<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function downloadCv($applicationId)

    {

        $application = Application::findOrFail($applicationId);

        if (auth()->id() !== $application->job->company_id) {

            abort(403, 'Unauthorized access');
        }

        return Storage::disk('local')->download($application->cv);
    }
}
