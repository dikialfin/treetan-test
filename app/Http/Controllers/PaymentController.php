<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use Illuminate\Http\Request;
use App\Services\PaymentGatewayService;
use App\Services\TransactionService;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="Treetan Test"
 * )
 */
class PaymentController extends Controller
{
    private $paymentGateway;
    private $transactionService;

    public function __construct(PaymentGatewayService $paymentGatewayService, TransactionService $transactionService)
    {
        $this->paymentGateway = $paymentGatewayService;
        $this->transactionService = $transactionService;
    }

    /**
     * @OA\Post(
     * path="/api/payment",
     * summary="Membuat Transaksi Baru dan Memproses Pembayaran",
     * tags={"Payment"},
     * description="Membuat entri transaksi dan detailnya, lalu memanggil gateway pembayaran pihak ketiga.",
     * @OA\RequestBody(
     * required=true,
     * description="Daftar produk yang akan dibeli.",
     * @OA\JsonContent(
     * required={"products"},
     * @OA\Property(
     * property="products",
     * type="array",
     * description="Daftar produk yang akan diproses. (Minimal 1 produk)",
     * minItems=1,
     * @OA\Items(
     * @OA\Property(property="name", type="string", example="test", description="Nama produk. (Max 255)"),
     * @OA\Property(property="price", type="number", format="float", example=1000, description="Harga produk. (Minimal 1000)"),
     * @OA\Property(property="quantity", type="integer", example=5, description="Kuantitas. (Minimal 1)")
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Transaksi dan pembayaran berhasil dibuat.",
     * @OA\JsonContent(ref="#/components/schemas/TransactionResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Kegagalan sistem internal, gagal menyimpan data, atau kegagalan dari gateway pembayaran.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="oops terjadi kesalahan ketika mencoba mengakses aplikasi pihak kedua")
     * )
     * )
     * )
     */
    public function create(StorePaymentRequest $request)
    {
        $products = json_decode($request->getContent(), true)['products'];
        $amount = collect($products)->sum(
            fn($product) => $product['price'] * $product['quantity']
        );

        $transaction = $this->transactionService->create($products, $amount);

        $response = $this->paymentGateway->createPayment([
            "transaction_id" => $transaction->id,
            "amount" => $transaction->amount,
            "products" => $products
        ]);

        if (!$response["success"]) {
            $this->transactionService->DeleteTransaction($transaction);
            return response()->json($response['message'], 500);
        }

        $transactionData = $this->transactionService->updateWithPaymentDetails($transaction, $response['data']);

        return response()->json([
            "transaction_id" => $transactionData->id,
            "reference" => $transactionData->reference,
            "payment_method" => $transactionData->payment_method,
            "payment_code" => $transactionData->pay_code,
            "customer_name" => $transactionData->customer_name,
            "customer_email" => $transactionData->customer_email,
            "customer_phone" => $transactionData->customer_phone,
            "amount" => $transactionData->amount,
            "expired" => $transactionData->expired_time,
            "order_detail" => $transactionData->details,
            "payment_instruction" => $transactionData->instruction_step
        ], 201);
    }

    /**
     * @OA\Get(
     * path="/api/payment/{id_transaction}",
     * summary="Cek Status Pembayaran",
     * tags={"Payment"},
     * description="Memeriksa status transaksi terkini melalui gateway pembayaran pihak ketiga.",
     * @OA\Parameter(
     * name="id_transaction",
     * description="ID unik (UUID) dari Transaksi yang ingin diperiksa.",
     * required=true,
     * in="path",
     * @OA\Schema(
     * type="string",
     * format="uuid",
     * example="019a7bfa-cbf2-7365-bac6-e8abc2118838"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Pengecekan status berhasil dilakukan.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Status transaksi saat ini DIBAYAR")
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Gagal menghubungi gateway pembayaran atau status transaksi tidak ditemukan.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Transaksi tidak ditemukan atau gagal menghubungi API.")
     * )
     * )
     * )
     */
    public function check(Request $request, $transactionId)
    {
        $response = $this->paymentGateway->checkPayment($transactionId);

        if (!$response["success"]) {
            return response()->json($response['message'], 500);
        }

        return response()->json(["message" => $response['message']], 200);
    }
}
