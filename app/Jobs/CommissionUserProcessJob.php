<?php
namespace App\Jobs;
use App\Models\CommissionProcess;
use App\Models\CommissionUser;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CommissionUserProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Set the batch size (number of users to settle in each batch)
        $batchSize = 1000;

        // Get all commission processes
        $commissionProcesses = CommissionProcess::where('is_processed', false)->get();

        // Start batch processing
        foreach ($commissionProcesses as $commissionProcess) {
            // Retrieve related commission users using offset and limit
            $offset = 0;
            do {
                $commissionUsers = CommissionUser::where('organization_id', $commissionProcess->organization_id)
                    ->offset($offset)
                    ->limit($batchSize)
                    ->get();

                // Process the batch of commission users
                foreach ($commissionUsers as $commissionUser) {
                     // Example: Calculate userAmount (replace this with your actual logic)
                    $userAmount = ($commissionUser->percentage / 100) * $commissionProcess->amount;

                    // Example: Update the user's wallet balance (bulk update)
                    $userWallet = Wallet::firstOrNew(['user_id' => $commissionUser->user_id]);
                    $userWallet->balance += $userAmount;
                    $userWallet->save();

                    Transaction::create([
                        'user_id' => $commissionUser->user_id,
                        'amount' => $userAmount,
                        'short_code' => $commissionUser->short_code,
                        'description' => 'credit',
                        'organization_id' => $commissionProcess->organization_id,
                        'transaction_name' => 'Incomming',
                        'transaction_type' => 'credit', // or 'debit' if applicable
                        // Add other relevant fields to the transaction history table
                    ]);
                }
                $commissionProcess->is_processed = true;
                $commissionProcess->save();

                // Increment offset for the next batch
                $offset += $batchSize;
            } while ($commissionUsers->count() > 0); // Continue processing until no more commission users are found
        }
    }
}
