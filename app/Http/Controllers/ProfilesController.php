<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfilesController extends Controller
{
    public function show(User $user)
    {

      return view( 'profiles.show', [
        'user' => $user,
        'tweets' => $user->tweets()->paginate(20)
      ]);
    }

    public function edit(User $user)
    {
      $this->authorize( 'edit', $user );
      //abort_if( $user->isNot( current_user() ), 404 );
       return view( 'profiles.edit', compact( 'user' ) );
    }

    public function update(User $user)
    {
      $data =request()->validate(
        [
          'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user) ],
          'name' => ['required', 'string', 'max:255'],
          'avatar' => [ 'file'],
          'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
          'password' => [
                'string',
                'required',
                'min:8',
                'max:255',
                'confirmed'
            ],
        ]);
//need to password hash 
      //$data['password'] = bcrypt( request('password') ); 
      //Or another way used / setPasswordAttribute($value) on User Model
      if ( request('avatar') ) {
        $data['avatar'] = request('avatar')->store('avatars');
      }
      
      $user->update($data);
      return redirect( $user->path() );
    }
}
