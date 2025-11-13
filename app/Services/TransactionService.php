<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Str;

class TransactionService
{
    public function create(array $products, float $amount): Transaction
    {
        $transaction = Transaction::create([
            "amount" => $amount,
        ]);

        $productsData = array_map(function ($product) use ($transaction) {
            return [
                "id" => (string) Str::uuid(),
                "id_transaction" => $transaction->id,
                "name" => $product["name"],
                "price" => $product["price"],
                "quantity" => $product["quantity"],
            ];
        }, $products);

        TransactionDetail::insert($productsData);

        return $transaction;
    }

    public function updateWithPaymentDetails(Transaction $transaction, array $paymentData): Transaction
    {
        $transaction->update([
            'reference' => $paymentData["reference"],
            'payment_method' => $paymentData["payment_method"],
            'payment_name' => $paymentData["payment_name"],
            'pay_code' => $paymentData["pay_code"],
            'expired_time' => timestampToDateTime($paymentData["expired_time"]),
            'instruction_title' => $paymentData["instructions"][0]["title"],
            'instruction_step' => $paymentData["instructions"][0]["steps"],
        ]);

        return $transaction;
    }

    public function DeleteTransaction(Transaction $transaction) {
        TransactionDetail::where('id_transaction', $transaction->id)->delete();
        Transaction::where('id', $transaction->id)->delete();
    } 

    public function UpdateTransaction(Transaction $transaction, array $dataUpdate) {
        $transaction->update($dataUpdate);
        return $transaction;
    }
}
