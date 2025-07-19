<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\ImageHelper;
use App\Mail\UserMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fullname',
        'username',
        'email',
        'password',
        'is_admin',
        'download_access',
        'profile_image',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];
    public $timestamps = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getProfileImageUrl()
    {
        return $this->profile_image ? Storage::url('assets/images/profile_photos/' . $this->profile_image) : '';
    }

    // send email
    public function sendEmailCreateUserNotification($password = '', $attachment_paths = [], $view = 'emails.user_created', $subject = 'User Created')
    {
        $logoPath = 'assets/images/company/company-logo.png';
        $logoBase64 = ImageHelper::getImageAsBase64($logoPath);

        $data = [
            'fullname' => $this->fullname,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $password,
            'logoBase64' => $logoBase64,
        ];

        Mail::to($this->email)->send(new UserMail(
            $data,
            $view,
            $subject,
            $attachment_paths
        ));
    }

    public function sendEmailUpdateUserNotification(
        $password = '',
        $attachment_paths = [],
        $old_email = '',
        $new_email = '',
        $old_fullname = '',
        $new_fullname = '',
        $view = 'emails.user_updated',
        $subject = 'User Updated'
    ) {
        $logoPath = 'assets/images/company/company-logo.png';
        $logoBase64 = ImageHelper::getImageAsBase64($logoPath);

        $data = [
            'old_fullname' => $old_fullname,
            'new_fullname' => $new_fullname,
            'old_email' => $old_email,
            'new_email' => $new_email,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $password,
            'logoBase64' => $logoBase64,
        ];

        Mail::to($this->email)->send(new UserMail(
            $data,
            $view,
            $subject,
            $attachment_paths
        ));
    }
}
