<?php

namespace App\Http\Controllers;

use App\Tweet;
use Illuminate\Http\Request;

class TweetsController extends Controller
{

    public function index()
    {   
        $tweets = auth()->user()->timeline();
        return view('tweets.index', compact('tweets'));
    }
    
    public function store(){
      $attributes = request()->validate(['body' => 'required | max:255']);

      Tweet::Create([
        'user_id' => auth()->id(),
        'body' => $attributes['body']
      ]);

      return redirect()->route('home');
    }
}
