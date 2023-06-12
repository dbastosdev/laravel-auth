<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'authority',
    ];

    /**
     * Declaração do relacionamento inverso Role x User.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'roles_users', 'user_id', 'role_id');
    }

}
