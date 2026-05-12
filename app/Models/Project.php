<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'repo_url',
        'prd_content',
        'prd_requirements',
        'spec_content',
    ];

    protected $casts = [
        'prd_requirements' => 'array',
    ];

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class);
    }
}
