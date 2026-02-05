<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicantRequest;
use App\Http\Resources\ApplicantResource;
use App\Models\Applicant;
use App\Services\FileUploadService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    use HttpResponses , Notifiable;
    public function index(Request $request)
    {
        $applicants = Applicant::query()->filter($request->all())->latest()->paginate(10);

        return $this->success($applicants, 'Applicants retrieved successfully');

    }
    public function show($id)
    {
        $applicant = Applicant::find($id);
        if (!$applicant) {
            return $this->error(null, 'Applicant not found', 404);
        }
        return $this->success(new ApplicantResource($applicant), 'Applicant retrieved successfully', 200);
    }
    public function update(ApplicantRequest $request , FileUploadService $fileService)
    {
        $user = Auth::user();


        $applicant = Applicant::where('user_id', $user->id)->first();

        if (!$applicant) {
            return $this->error(null, 'Applicant not found', 404);
        }

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($applicant->image) {
                $fileService->delete($applicant->image);
            }
            $imagePath = $fileService->upload( $request->file('image') , 'applicant_images');
            $validated['image'] = $imagePath;
        }


        $applicant->update($validated);

        return $this->success(new ApplicantResource($applicant), 'Applicant updated successfully', 200);
    }

    public function destroy($id)
    {
        $current_user = Auth::user();
        $applicant = Applicant::find($id);
        if (!$applicant){
            return $this->error(null ,'applicant not found' , 404);
        }
        if ($current_user->id !== $applicant->user_id){
            return $this->error(null , 'not allow to do this',403);
        }
        $applicant->delete();
        return $this->success(null , 'applicant deleted succesfully',200);
    }
}
