<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_code',
        'status',
        'total_price',
        'shipping_cost',
        'payment_method',
        'shipping_address',
        'notes',
        'transfer_bank_name',
        'transfer_account_name'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'pending') {
            return $this->payment_method === 'transfer' ? 'Menunggu Verifikasi Pembayaran' : 'Menunggu Konfirmasi';
        }

        return match($this->status) {
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'delivered'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'yellow',
            'processing' => 'blue',
            'shipped'    => 'purple',
            'delivered'  => 'green',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }

    public function getGrandTotalAttribute(): float
    {
        return $this->total_price + $this->shipping_cost;
    }
}
