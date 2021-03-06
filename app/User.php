<?php

namespace App;

use App\Tweet;
use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, Followable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = [];

    /**
     * The attributes that shoprotected $guarded = [];uld be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarAttribute($value){
        return asset($value ? : '/images/default-avatar.jpeg');
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value); //or
        //$this->attributes['password'] = Hash::make($value);
    }


    public function timeline(){
        $friends = $this->follows->pluck('id');
        // $ids->push($this->id);
        return Tweet::whereIn('user_id', $friends)
                ->orWhere('user_id', $this->id)
                ->latest()->paginate(20);
    }

    public function tweets(){
        return $this->hasMany(Tweet::class)->latest();
    }



    public function path( $append='' ){

        $path = route('profile',  $this->username);
        if ( $append ) {
            $path = $path .'/'. $append;
        }

        return $path;
        // return $append ? "{ $path }/{ $append }" : $path;
    }
}
