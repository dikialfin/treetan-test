<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function receiveCallback(Request $request)
    {
        Log::alert("Callback Received");
        Log::alert($request);

        try {
            $transaction = Transaction::find($request["merchant_ref"]);
            $this->transactionService->UpdateTransaction($transaction, [
                "status" => $request["status"]
            ]);
        } catch (\Throwable $th) {
            Log::error("Failed to update transaction status : ".$th->getMessage());
        }

        return response()->json(["success" => 'true'], 200);
    }
}
