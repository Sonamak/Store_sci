<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'state',
        'city',
        'educational_attainment_id',
        'general_specialization_id',
        'specialization_id',
        'password',
        'role',
        'locale',
        'download_count',
        'whatsapp_count',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'api_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'country_name',
        'state_name',
    ];

    // Autorun
    public static function boot() {
        parent::boot();

        static::creating(function($model) {
            $model->api_token = Str::random(60);
            $model->email_verified_at = Carbon::now();
        });
    }

    // Relationships
    public function educationalAttainment() {        
        return $this->belongsTo('App\Models\UserField', 'educational_attainment_id', 'id');
    }

    public function generalSpecialization() {        
        return $this->belongsTo('App\Models\UserField', 'general_specialization_id', 'id');
    }

    public function specialization() {        
        return $this->belongsTo('App\Models\UserField', 'specialization_id', 'id');
    }

    // Attributes
    public function getCountryNameAttribute() {
        if(empty($this->country)) {
            return null;
        }
        
        $name = DB::table('countries')
            ->select('name')
            ->where('iso2', $this->country)
            ->first();

        return $name->name ?? null;
    }
    public function getStateNameAttribute() {
        if(empty($this->state)) {
            return null;
        }
        
        $name = DB::table('country_states')
            ->select('name')
            ->where('iso2', $this->state)
            ->first();

        return $name->name ?? null;
    }
}
