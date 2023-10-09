<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Fetch all users
Route::get('/users', function () {
    $users = User::all();
    return response()->json($users);
});

// Add a new user
Route::post('/users', function (Request $request) {
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
    ]);
    
    $user = new User;
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json(['message' => 'User created successfully', 'user' => $user]);
});

// Fetch a specific user
Route::get('/users/{id}', function ($id) {
    $user = User::find($id);
    if ($user) {
        return response()->json($user);
    } else {
        return response()->json(['message' => 'User not found'], 404);
    }
});

// Update a user's details
Route::put('/users/{id}', function (Request $request, $id) {
    $user = User::find($id);
    if ($user) {
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email') && $user->email != $request->email) {
            $request->validate(['email' => 'email|unique:users,email']);
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    } else {
        return response()->json(['message' => 'User not found'], 404);
    }
});

// Delete a user
Route::delete('/users/{id}', function ($id) {
    $user = User::find($id);
    if ($user) {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    } else {
        return response()->json(['message' => 'User not found'], 404);
    }
});
