<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\EmailVerificationRequest;
use Otp;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\Mail;


class EmailVerificationController extends Controller
{
    private $otp;

    public function __construct(){
    $this->otp = new Otp;
}

    public function sendEmailVerification(Request $request){
        $user = $request->user();
        $user->notify(new EmailVerificationNotification());
        $success['success'] = true;
        return response()->json($success,200);
    }

    public function email_verification(EmailVerificationRequest $request){
        $otp2 = $this->otp->validate($request->email, $request->otp);
        if(!$otp2->status)
            {
            return response()->json(['error'=> $otp2],401);
        }
        $user = User::where('email',$request->email)->first();
        $user->update(['email_verified_at'=> now()]);
        $success['success'] = true;
        return response()->json($success,200);
    }

























    // public function sendOtp($user)
    // {
    //     $otp = rand(1000, 9999);
    //     $time=time();
    //     EmailVerification::updateOrCreate(
    //         ['email' => $user->email],
    //         [
    //             'email' => $user->email,
    //             'otp' => $otp,
    //             'created_at' => $time 
    //         ]
    //         );
    //         $data['email'] = $user->email;
    //         $data['title'] = 'Mail Verification';
    //         $data['body'] = 'Your OTP is:-'.$otp;
    //         Mail::raw('Your OTP is:-'.$otp,function($message) use ($data){
    //             $message->to($data['email'])->subject($data['title']);
    //         });
        
    //     }

    //     public function verification(Request $request)
    //     {
    //         $email = $request->input('email'); // Assuming you're sending the email address in the request
        
    //         $user = User::where('email', $email)->first();
            
    //         if (!$user || $user->is_verified == 1) {
    //             return response()->json(['message' => 'Invalid user or user is already verified'], 400);
    //         }
            
    //         $this->sendOtp($user); // You need to implement the sendOtp method
            
    //         return response()->json(['message' => 'OTP sent successfully', 'email' => $email], 200);
    //     }
        

    //     public function verifiedOtp(Request $request)
    //     {
    //         try {
    //             $user = User::where('email', $request->email)->first();
    //             $otpData = EmailVerification::where('otp', $request->otp)->first();
        
    //             if (!$otpData) {
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'You entered the wrong OTP'
    //                 ], 401);
    //             } else {
    //                 // You can remove the OTP expiration check
    //                 User::where('email', $request->email)->update([
    //                     'is_verified' => 1
    //                 ]);
        
    //                 // You may also delete the email verification record, assuming it's a one-time OTP.
    //                 $otpData->delete();
        
    //                 return response()->json([
    //                     'status' => true,
    //                     'message' => 'Email has been verified'
    //                 ], 200);
    //             }
    //         } catch (\Throwable $th) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => $th->getMessage()
    //             ], 500);
    //         }
    //     }
        
    //             public function resendOtp(Request $request) {
    //                 try {
    //                     $user = User::where('email', $request->email)->first();
    //                     $otpData = EmailVerification::where('email', $request->email)->first();
                
    //                     if (!$otpData) {
    //                         return response()->json([
    //                             'status' => false,
    //                             'message' => 'No OTP data found for this email'
    //                         ], 404); // You can choose an appropriate HTTP status code
    //                     }
                
    //                     $currentTime = time();
    //                     $time = $otpData->created_at->timestamp; // Get the timestamp of created_at
                        
    //                     // Check if the OTP is still valid for 10 minutes (600 seconds)
    //                     if ($currentTime >= $time && $time >= $currentTime - 600) {
    //                         return response()->json([
    //                             'status' => false,
    //                             'message' => 'Please try after some time'
    //                         ], 401);
    //                     } else {
    //                         $this->sendOtp($user);
    //                         return response()->json([
    //                             'status' => true,
    //                             'message' => 'OTP has been sent'
    //                         ], 200);
    //                     }
    //                 } catch (\Throwable $th) {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => $th->getMessage()
    //                     ], 500);
    //                 }
    //             }
                
                

}
