<?php

namespace App\Http\Controllers\v1;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommissionUser;
use App\Models\Wallet;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashnoardInfo(Request $request)
    {
        // Get the authenticated user using the provided bearer token
        $user = Auth::user();
        $currentDate = Carbon::today();
        $userId = $user->id;
        $commissions = CommissionUser::where('user_id', $userId)->orderBy('created_at', 'desc')->take(20)->get();
        $commissionCount = CommissionUser::where('user_id', $userId)->count();
        $wallet = Wallet::where('user_id', $userId)->first();
        $creditTransactionCount = Transaction::where('user_id', $userId)->where('transaction_type', 'credit')->count();
        $debitTransactionCount = Transaction::where('user_id', $userId)->where('transaction_type', 'debit')->count();
        $transactions = Transaction::where('user_id', $userId)->orderBy('created_at', 'desc')->take(20)->get();
        $debitTransactionSum = Transaction::where('user_id', $userId)
        ->where('transaction_type', 'debit')
        ->whereDate('created_at', $currentDate)
        ->sum('amount');

         // Calculate the sum of amount for today's credit transactions for the given user
        $creditTransactionSum = Transaction::where('user_id', $userId)
        ->where('transaction_type', 'credit')
        ->whereDate('created_at', $currentDate)
        ->sum('amount');

        $creditTransactiontoday = Transaction::where('user_id', $userId)
        ->where('transaction_type', 'credit')
        ->whereDate('created_at', $currentDate)
        ->count();

        // You can now use the $user object to access the authenticated user's data
        return response()->json([
            'message' => 'User selected successfully',
            'status' => true,
            'user' => $user, 
            'commissions'=>$commissions,
            'transactions'=>$transactions,
            'commissionCount'=>$commissionCount,
            'wallet'=> $wallet->balance,
            'debitToday' => number_format($debitTransactionSum,2),
            'creditToday' => number_format($creditTransactionSum,2),
            'inwardTransaction'=> $creditTransactionCount,
            'outwardTransaction' => $debitTransactionCount,
            'transactionToday'  => $creditTransactiontoday,
            ]);
    }
}
