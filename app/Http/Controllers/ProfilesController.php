<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function index($user)
    {
        $user = User::findOrFail($user);

        return view('profiles.index', [
            'user' => $user
        ]);
    }

    // Penulisannya bisa kayak diatas pake findorfail, ato juga bisa kayak dibawah langsung diimport nama kelasnya. Bisa juga yang dibawah return view nya diganti jadi compact
    public function edit(\App\User $user)
    // Sebenenya nggak usah pake \App\User karena udah diimport duluan diatas. Tapi sebagai catetan aja
    {
        // Biar yang bisa update di protect
        $this->authorize('update', $user->profile);
        
        return view('profiles.edit', compact('user'));
    }

    public function update(User $user)
    {
        // Ini juga di protect
        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);

        // Sebenernya bisa ditulis kayak begini
        // $user->profile->update($data);
        
        // Ini untuk nambahin extra layer protection
        auth()->user()->profile->update($data);

        return redirect("/profile/{$user->id}");
    }
}