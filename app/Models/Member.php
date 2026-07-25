<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = ['adhesion_id', 'email', 'password', 'show_in_directory', 'restore_token'];
    protected $hidden   = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'          => 'hashed',
            'show_in_directory' => 'boolean',
        ];
    }

    public function adhesion(): BelongsTo
    {
        return $this->belongsTo(Adhesion::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\MemberResetPassword($token));
    }
}
