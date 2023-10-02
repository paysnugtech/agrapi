<?php

namespace App\Http\Controllers\v1;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function transaction(Request $request)
    {
        // Get the authenticated user using the provided bearer token
        $user = Auth::user();
        $userId = $user->id;
          $creditTransactionCount = Transaction::where('user_id', $userId)->where('transaction_type', 'credit')->count();
        $debitTransactionCount = Transaction::where('user_id', $userId)->where('transaction_type', 'debit')->count();
        $transactions = Transaction::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        // You can now use the $user object to access the authenticated user's data
        return response()->json([
            'message' => 'Transaction fetch successfully',
            'transactions'=>$transactions,
            'inwardTransaction'=> $creditTransactionCount,
            'outwardTransaction' => $debitTransactionCount,
            ]);
    }
}
