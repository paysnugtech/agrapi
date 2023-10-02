<?php

namespace App\Http\Controllers\v1;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
class InviteController extends Controller
{
    public function getInvite(Request $request){
        $existingInvite = Invite::where('invite_id', $request->input('invite_id'))->first();
        if($existingInvite){
            return response()->json(['message' => 'User Invitation Fetch successfully','status' => true, 'data' => $existingInvite,], 200);
        }
        else{
            return response()->json(['message' => 'User Invitation Fetch Failed','status' => false, 'data' => $existingInvite,], 422);
        }

    }
    public function storeInvite(Request $request)
    {
        $inviteId = Str::upper(md5(uniqid($request)));
        $validator = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string|min:8',
            'user_type' => 'required|string|max:255',
        ]);

        $existingInvite = Invite::where('email', $request->input('email'))->first();
        if ($existingInvite) {
            return new JsonResponse([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ],
            ], 422);
        }

        // Create a new invite record in the database
        $invite = Invite::create([
            'name' => $request->input('name'),
            'phone_number' => $request->input('phone_number'),
            'invite_id' => $inviteId,
            'email' => $request->input('email'),
            'user_type' => $request->input('user_type'),
        ]);

        $response = Http::post('https://paysnug.com/mail_server/mail', [
            'mailType' =>'activateagr',
            'emailAddress' => $request->input('email'),
            'link' => 'https://agr.paysnug.com/invite/'.$inviteId,
            'note' => "Settlement Batch " . date("Y-m-d")
            // Add other necessary data for the transfer
        ]);

        // Optionally, you can perform other actions or return a response
        return response()->json(['message' => 'Invite created successfully', 'data' => $invite], 201);
    }

    public function registerInvite(Request $request)
    {
        $uniqueRefererCode = Str::random(8);
        
        $validator = Validator::make($request->all(), [
            'invite_id' => 'required|string|max:255',

            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $existingInvite = Invite::where('invite_id', $request->input('invite_id'))->first();
        if (!$existingInvite) {
            return new JsonResponse([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['Invitation Not found'],
                ],
            ], 422);
        }

        // Create a new invite record in the database
        $user = User::create([
            'name' => $existingInvite->name,
            'email' => $existingInvite->email,
            'phone_number' => $existingInvite->phone_number,
            'password' => Hash::make($request->input('password')),
            'referer_code' => $uniqueRefererCode,
            'user_type' => $existingInvite->user_type,
        ]);
        $token = $user->createToken('user')->plainTextToken;
        $existingInvite->delete();

        // Optionally, you can perform other actions or return a response
        return response()->json(['message' => 'User created successfully', 'data' => $user,'token' =>$token], 201);
    }
}
