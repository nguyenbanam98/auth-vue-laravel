<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Api\ResetRequest;
use App\Http\Requests\Api\ForgetRequest;

class ForgotController extends Controller
{
    public function forgot(ForgetRequest $request)
    {

        $email = $request->email;
     
        if (User::where('email', $email)->doesntExist()) {
            return response([
                'message' => 'User doen\t exists!',
            ], 400);
        }

        $token = Str::random(30);

        try {
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now(),
            ]);

            // Send mail

            Mail::send('mail.forgot', ['token' => $token], function (Message $message) use($email) {
                $message->to($email);
                $message->subject('Reset your password');
            });

            return response([
                'message' => 'Check your email',
            ], 200);
        } catch (\Throwable $e) {

            return response([
                'message' => $e->getMessage(),
            ], 400);
        }        

    }

    public function reset(ResetRequest $request)
    {
        $token = $request->token;

        $passwordReset = DB::table('password_resets')->where('token', $token)->first();

        if(!$passwordReset) {
            return response([
                'message' => 'Invalid token!'
            ], 400);
        }

        $user = User::where('email', $passwordReset->email)->first();
        
        if(!$user) {
            return response([
                'message' => 'User does not exist!'
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response([
            'message' => 'succes'
        ], 200);
    }
}
 