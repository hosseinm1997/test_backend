<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @property Organization organizationRelation
 * @package App\Models
 */
class User extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile',
        'national_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    protected $guard_name = 'api';

    public function sendPasswordResetNotification($token)
    {
        $address = env('PANEL_URL') . '/recovery-password?token=' . $token .'&mobile='.request()->mobile;

        sendSmsByPattern(request()->mobile,
            config('pattern.reset_password'),
            array('link' => $address)
        );
    }

    public function getEmailForPasswordReset()
    {
        return $this->mobile;
    }

    public function organizationRelation()
    {
        return $this->hasOne(Organization::class, 'owner_user_id', 'id');
    }

}
