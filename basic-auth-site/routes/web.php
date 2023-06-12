<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Role;
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

// Rota relacionamento N:N User x Role
Route::get('/user-role', function () {

    # Retorna dados via user
    //$user = User::find(1); // Seleciona o primeiro usuário da tabela
    //return $user->roles()->first(); // Seleciona e retorna o primeiro registro de papel
    //return response()->json($user->roles); // retorna todos os roles associados a um usuário

    # Retonar dados via Role
    //$role = Role::find(2);
    //return response()->json($role->users); // retorna todos os roles associados a um usuário

    #Relaciona dados
    // resgata um user
    $user = User::with('roles')->find(2);
    // cria um role
    $newRole = Role::create(['authority'=>'writer']);
    // relaciona
    $user->roles()->save($newRole);
    return response()->json($user->roles);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
