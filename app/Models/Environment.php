<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
    ];

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
