<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Positions extends Model
{
    use HasFactory;

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }
}
