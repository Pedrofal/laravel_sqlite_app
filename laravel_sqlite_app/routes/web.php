<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', function () {
    $users = User::all();
    return view('users.index', ['users' => $users]);
});

// Store a new user
Route::post('/users', function (Request $request) {
    $user = new User;
    $user->username = $request->username;
    $user->userpassword = bcrypt($request->userpassword);
    $user->save();

    return redirect('/users');
});

// Edit an existing user (show the form)
Route::get('/users/{id}/edit', function ($id) {
    $user = User::find($id);
    return view('users.edit', ['user' => $user]);
});

// Update the user
Route::post('/users/{id}', function (Request $request, $id) {
    $user = User::find($id);
    $user->username = $request->username;
    if ($request->has('userpassword') && $request->userpassword) {
        $user->userpassword = bcrypt($request->userpassword);
    }
    $user->save();

    return redirect('/users');
});

// Delete a user
Route::delete('/users/{id}', function ($id) {
    User::destroy($id);
    return redirect('/users');
});