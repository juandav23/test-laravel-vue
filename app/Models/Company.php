<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class Company extends Model
{
    use HasFactory;

    public function positions()
    {
        return $this->hasMany(Positions::class);
    }
}
