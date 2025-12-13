<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicantRequest;
use App\Http\Requests\ApplicantUpdateRequest;
use App\Http\Resources\ApplicantResource;
use App\Models\Applicant;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    use HttpResponses , Notifiable;
    public function index()
    {
        $applicants = Applicant::all();
        return $this->success(ApplicantResource::collection($applicants), 'Applicants retrieved successfully', 200);
    }
    public function show($id)
    {
        $applicant = Applicant::find($id);
        if (!$applicant) {
            return $this->error(null, 'Applicant not found', 404);
        }
        return $this->success(new ApplicantResource($applicant), 'Applicant retrieved successfully', 200);
    }
    public function update(ApplicantRequest $request)
    {
        $user = Auth::user();


        $applicant = Applicant::where('user_id', $user->id)->first();

        if (!$applicant) {
            return $this->error(null, 'Applicant not found', 404);
        }

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('applicant_images', 'public');
            $validated['image'] = $imagePath;
        }


        $applicant->update($validated);

        return $this->success(new ApplicantResource($applicant), 'Applicant updated successfully', 200);
    }

    public function destroy($id)
    {
        $applicant = Applicant::find($id);
        if (!$applicant) {
            return $this->error(null, 'Applicant not found', 404);
        }
        $applicant->delete();
        return $this->success(null, 'Applicant deleted successfully', 200);
    }
}
