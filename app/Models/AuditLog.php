<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'user_type', 'entity_type', 'entity_id',
        'title', 'description'
    ];

    // protected $casts = [
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    // ];

    /**
     * The actor who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Dynamic relationship to the target entity
     */
    public function entity(): ?Model
    {
        if (!$this->entity_type || !$this->entity_id) {
            return null;
        }

        $modelClass = $this->resolveModelClass();

        return $modelClass ? $modelClass::find($this->entity_id) : null;
    }

    /**
     * Map entity_type string to fully-qualified Model class
     */
    protected function resolveModelClass(): ?string
    {
        $map = [
            'transaction'   => \App\Models\Transaction::class,
            'claim'         => \App\Models\ClaimRequest::class, // your model name
            'user'          => \App\Models\User::class,
            'session'       => \App\Models\UserSession::class,
            'login'         => \App\Models\UserSession::class, // if you track logins separately
        ];

        return $map[$this->entity_type] ?? null;
    }

    /**
     * Accessor to get entity with type hint
     */
    public function getEntityAttribute(): ?Model
    {
        return $this->entity();
    }
}