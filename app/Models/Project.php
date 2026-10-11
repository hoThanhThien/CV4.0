<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'github_url', 'demo_url', 'featured', 'order'
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'project_skill');
    }

    public function technologies()
    {
        return $this->skills();
    }
}
