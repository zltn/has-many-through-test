<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'environment_id',
    ];

    public function environment()
    {
        return $this->belongsTo(Environment::class);
    }

}
