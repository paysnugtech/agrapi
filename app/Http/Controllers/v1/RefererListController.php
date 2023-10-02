<?php

namespace App\Http\Controllers\v1;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommissionUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RefererListController extends Controller
{
    public function referer(Request $request)
    {
        // Get the authenticated user using the provided bearer token
        $user = Auth::user();
        $userId = $user->id;
          $refererlistCount = CommissionUser::where('user_id', $userId)->count();
          $refererlist = CommissionUser::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        // You can now use the $user object to access the authenticated user's data
        return response()->json([
            'message' => 'Referer fetch successfully',
            'refererlist'=>$refererlist,
            'refererlistCount'=> $refererlistCount,
            ]);
    }

    public function createReferer(Request $request)
    {
        $existinguser = User::where('referer_code', $request->input('referer_code'))->first();
        $percentage = CommissionUser::where('organization_id', $request->input('organization_id'))->sum('percentage')+$request->input('percentage');
        if($percentage>100){
            $remainingPercentage = 100 - CommissionUser::where('organization_id', $request->input('organization_id'))->sum('percentage');
            return response()->json(['message' => "You can only add CommissionUsers with a total percentage of 100 or less. Remaining percentage to add: $remainingPercentage%"], 400);
            exit();
        }

        $exists = CommissionUser::where('user_id', $existinguser->id)
              ->where('organization_id', $request->input('organization_id'))
              ->first();
        if($exists){
                // Update the attributes
           // Update the attributes
        // /$exists->short_code = $request->input('short_code');
        $exists->organization_id = $request->input('organization_id');
        $exists->percentage = $exists->percentage + $request->input('percentage');
        $exists->organization_name = $request->input('organization_name');

        // Save the changes
        $exists->save();
            return response()->json([
                'message' => 'Referer Updated Successfully',
                ],200);
                exit();
        }
        if($existinguser){

        $create = CommissionUser::create([
            'user_id' => $existinguser->id,
            'short_code' => $request->input('short_code'),
            'organization_id' => $request->input('organization_id'),
            'referer_code' =>  $request->input('referer_code'),
            'percentage' =>  $request->input('percentage'),
            'organization_name' =>  $request->input('organization_name'),

        ]);

        // You can now use the $user object to access the authenticated user's data
        return response()->json([
            'message' => 'Referer created successfully',
            'refererlist'=>$create,
            ]);
        }
        else{

            return response()->json([
                'message' => 'Referer created failed',
                ],400);
        }
    }
}
