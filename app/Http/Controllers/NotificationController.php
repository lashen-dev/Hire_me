<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use HttpResponses;
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = null;

        if ($user->company) {
            $notifications = $user->company->notifications()->get();
        } elseif ($user->applicant) {
            $notifications = $user->applicant->notifications()->get();
        } else {
            return response()->json(['message' => 'No notifications found for this user.'], 404);
        }

        return $this->success($notifications, 'Notifications retrieved successfully', 200);
    }
}
