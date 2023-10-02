<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\CommissionUserProcessJob;
use App\Models\CommissionProcess;

class IncomingProcessController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Process the webhook data and retrieve the commission process details
        $commissionProcessData = $request->all();

        // Create a new commission process in the database
        $commissionProcess = CommissionProcess::create([
            'short_code' => $commissionProcessData['short_code'],
            'organization_id' => $commissionProcessData['organization_id'],
            'amount' => $commissionProcessData['amount'],
        ]);

        // Dispatch the CommissionUserProcessJob with the commission process ID
        dispatch(new CommissionUserProcessJob());

        // Return a response indicating that the webhook data has been received and the job is dispatched
        return response()->json(['message' => 'Webhook data received. Commission users settlement job dispatched successfully',$commissionProcess]);
    }
}
