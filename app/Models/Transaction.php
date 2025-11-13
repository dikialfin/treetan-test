<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 * schema="TransactionResponse",
 * title="Successful Transaction Response",
 * description="Struktur data respons setelah transaksi berhasil dibuat.",
 * @OA\Property(property="transaction_id", type="string", format="uuid", example="019a7bfa-cbf2-7365-bac6-e8abc2118838"),
 * @OA\Property(property="reference", type="string", example="DEV-T46885308267E4ICH"),
 * @OA\Property(property="payment_method", type="string", example="BRIVA"),
 * @OA\Property(property="payment_code", type="string", example="855127573817960"),
 * @OA\Property(property="customer_name", type="string", nullable=true, example="null"),
 * @OA\Property(property="customer_email", type="string", format="email", nullable=true, example="null"),
 * @OA\Property(property="customer_phone", type="string", example="08123456789", nullable=true),
 * @OA\Property(property="amount", type="number", format="float", example=15000),
 * @OA\Property(property="expired", type="string", format="date-time", example="14/11/25 06:50:25", description="Waktu kedaluwarsa dalam format dd/mm/yy H:i:s"),
 * @OA\Property(
 * property="order_detail", 
 * type="array", 
 * description="Daftar produk yang dibeli.",
 * @OA\Items(
 * @OA\Property(property="name", type="string", example="okok"),
 * @OA\Property(property="price", type="number", format="float", example=2500),
 * @OA\Property(property="quantity", type="integer", example=4)
 * )
 * ),
 * @OA\Property(property="payment_instruction", type="array", description="Langkah-langkah pembayaran.", @OA\Items(type="string", example="Login ke internet banking Bank BRI Anda"))
 * )
 */

class Transaction extends Model
{
    use HasUuids;

    protected $table = 'transactions';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'reference',
        'payment_method',
        'payment_name',
        'amount',
        'pay_code',
        'status',
        'expired_time',
        'instruction_title',
        'instruction_step',
        'created_at',
        'updated_at',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, 'id_transaction', 'id');
    }
}
