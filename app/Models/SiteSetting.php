<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['company_name', 'hero_title', 'hero_description', 'hero_image', 'delivery_charge'];

    protected $casts = ['delivery_charge' => 'decimal:2'];
}
