<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',            // PENTING: Tambahkan ini agar user_id bisa disimpan
        'biteship_order_id',
        'waybill_id',
        'courier_company',
        'courier_type',
        'receiver_name',
        'receiver_phone',
        'destination_address',
        'status',
        'price',
    ];

    /**
     * Relasi ke User
     * Ini yang membuat fungsi ::with('user') di controller bisa bekerja
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}