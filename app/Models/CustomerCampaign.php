<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCampaign extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'start_timestamp', 'voucher_id', 'status'];

    public $timestamps = false;    

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function voucher() {
        return $this->belongsTo(Voucher::class);
    }
}
