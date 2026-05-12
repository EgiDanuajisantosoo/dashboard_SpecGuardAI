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
        'spec_content',
    ];

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class);
    }
}
