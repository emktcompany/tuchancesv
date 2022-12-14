<?php

namespace App\TuChance\Models;

use App\Notifications\ResetPassword;
use App\TuChance\Contracts\Locateable as LocateableContract;
use App\TuChance\Contracts\Taggable as TaggableContract;
use App\TuChance\Eloquent\Locateable;
use App\TuChance\Eloquent\Taggable;
use App\Events\UserCreated;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, LocateableContract, TaggableContract
{
    use HasRoles, Locateable, Notifiable, SoftDeletes, Taggable, SearchableTrait;

    /**
     * Guard the model authenticates againts
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * Attributes that are mass assignable
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'country_id', 'state_id', 'city_id',
    ];

    /**
     * Attributes that should not be included when converting to array
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Attributes that should be casted to Carbon instances
     * @var array
     */
    protected $dates = [
        'login_at', 'last_login_at',
    ];

    /**
     * Searchable rules.
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'name'  => 10,
            'email' => 8,
        ],
    ];

    protected $dispatchesEvents = [
       'saved'   => UserCreated::class,
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->getRoleNames()->first(),
        ];
    }

    /**
     * Avatar as asset
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function avatar()
    {
        return $this->morphOne(Asset::class, 'assetable');
    }

    /**
     * Bidder related to the user, when role is bidder
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bidder()
    {
        return $this->hasOne(Bidder::class);
    }

    /**
     * Social logins by this user
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function socialLogins()
    {
        return $this->hasMany(SocialLogin::class);
    }

    /**
     * Login history
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function logins()
    {
        return $this->hasMany(Login::class);
    }

    /**
     * Candidate related to the user, when role is candidate
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function candidate()
    {
        return $this->hasOne(Candidate::class);
    }

    /**
     * Programas que
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function programs()
    {
        return $this->belongsToMany(Program::class);
    }

    /**
     * Send the password reset notification.
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function networkId($network)
    {
        return $this->socialLogins()
            ->where('network', $network)
            ->pluck('network_id')
            ->first();
    }
}
