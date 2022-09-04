<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['status'];

    public function customerCampaign() {
        return $this->hasOne(CustomerCampaign::class);
    }
}