<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymentGatewayService
{
    public function createPayment(array $data)
    {
        $paymentRequestData = [
            "method" => "BRIVA",
            "merchant_ref" => $data['transaction_id'],
            "amount" => $data['amount'],
            "customer_name" => "Edi Kaliper",
            "customer_email" => "edikaliper@gmail.com",
            "customer_phone" => "081234567890",
            "order_items" => $data["products"],
            "expired_time" => (time() + (24 * 60 * 60)),
            "signature" => signatureBuilder($data['transaction_id'],$data['amount']),
        ];

        
        $response = Http::withHeaders([
            "Authorization" => "Bearer ".env("API_KEY")
        ])->post(env("API_BASE_URL")."/transaction/create",$paymentRequestData);

        return json_decode($response->body(), true);
    }

    public function checkPayment($transactionId)
    {   
        $response = Http::withHeaders([
            "Authorization" => "Bearer ".env("API_KEY")
        ])->get(env("API_BASE_URL")."/transaction/check-status",[
            "reference" => $transactionId
        ]);

        return json_decode($response->body(), true);
    }
}
