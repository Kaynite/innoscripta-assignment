<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Password Reset
 */
class ResetPasswordController extends Controller
{
    /**
     * Send password reset code.
     */
    public function sendCode(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'message' => 'A password reset code will be sent to your email if it exists in our database.',
            ]);
        }

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token = fake()->numerify('######'),
            'created_at' => now(),
        ]);

        $user->sendPasswordResetNotification($token);

        return response()->json([
            'message' => 'A password reset code will be sent to your email if it exists in our database.',
        ]);
    }

    /**
     * Reset password.
     */
    public function reset(ResetPasswordRequest $request)
    {
        $token = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->first();

        if (! $token) {
            return response()->json([
                'message' => 'The password reset token is invalid or expired.',
            ], 400);
        }

        User::where('email', $request->email)->update([
            'password' => $request->password,
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Password reset successfully.',
        ]);
    }
}
