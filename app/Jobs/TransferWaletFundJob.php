<?php
namespace App\Jobs;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class TransferWaletFundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        \Log::info('TransferWaletFundJob: Started');
        // Get the users you want to transfer money from
        $users = User::all();

        foreach ($users as $user) {
            // Assuming you have the logic to calculate the amount to be transferred for each user
            $amount = $this->calculateTransferAmountForUser($user);

            // Skip users with insufficient balance (less than 1000)
            if ($amount < 100) {
                \Log::info("Skipping user $user->id: Insufficient balance for settlement");
                continue;
            }

            // Debit the customer and save the transaction
            $this->debitCustomerAndSaveTransaction($user, $amount);

            // Send settlement data to the external link
            $response = Http::post('https://paysnug.link/hooks/psgcommision/v1/paysnugcom.php', [
                'user_id' => $user->id,
                'amount' => $amount,
                'phone' => $user->phone_number,
                'note' => "Settlement Batch " . date("Y-m-d")
                // Add other necessary data for the transfer
            ]);

            // Check if the response is successful (200)
            if ($response->successful()) {
                \Log::info("Settlement data sent successfully for user $user->id");
            } else {
                \Log::error("Failed to send settlement data for user $user->id: " . $response->status());
            }
        }
        \Log::info('TransferWaletFundJob: Completed');
    }

    // Add your logic for calculating the transfer amount for each user
    private function calculateTransferAmountForUser(User $user)
    {
        // Add your calculation logic here, assuming you want to settle the entire wallet balance
        $wallet = Wallet::where('user_id', $user->id)->first();
        if ($wallet) {
            return $wallet->balance;
        }
        return 0;
    }

    // Add your logic for debiting the customer and saving the transaction
    private function debitCustomerAndSaveTransaction(User $user, $amount)
    {
        // Debit the customer's wallet balance
        $wallet = Wallet::where('user_id', $user->id)->firstOrFail();
        $wallet->balance -= $amount;
        $wallet->save();

        // Create a transaction record
        Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'short_code' => "nill",
            'description' => 'Batch-' . date("Y-M-D"),
            'organization_id' => "self",
            'transaction_name' => 'Settlement',
            'transaction_type' => 'debit', // or 'debit' if applicable
        ]);
    }
}
