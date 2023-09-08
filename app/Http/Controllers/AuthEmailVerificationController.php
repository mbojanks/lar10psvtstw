<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OrderShipped;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthEmailVerificationController extends Controller
{
    function verify($user_id, $hash, Request $request) {
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "Invalid/Expired url provided."], 401);
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return view('emails.verified'); // redirect()->to('/');
    }

    function resend(EmailVerificationRequest $request)
    {
        $user = auth()->user();
        /** @var \App\Models\User|null $user */
        if ($user->hasVerifiedEmail()) {
            return response()->json(["msg" => __("Email already verified.")], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(["msg" => __("Email verification link sent on your email id")]);
    }

    public function test(Request $request) {
        Mail::to('bmilosavljevic@gmail.com')->send(new OrderShipped());
        response('{"message":"Sent successfully"}', Response::HTTP_OK);
    }
}

