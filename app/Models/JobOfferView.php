<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOfferView extends Model
{
    use HasFactory;


    protected $fillable = [
        'job_offer_id',
        'user_id',
    ];
}
