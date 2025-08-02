<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetTokenModel extends Model
{
    protected $table = 'password_reset_tokens';
    public $timestamps = false;
    // set primary key
    protected $primaryKey = 'email';
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user associated with the password reset token.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
