<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

/* User */
Volt::route('dashboard', 'dashboard')->middleware(['auth'])->name('dashboard');
Volt::route('info', 'info')->middleware(['auth'])->name('info');

/* Admin */
Volt::route('usuarios', 'admin.user.index')->middleware(['auth', 'permission:mostrar usuarios'])->name('usuarios');
Volt::route('roles', 'admin.role.index')->middleware(['auth', 'permission:mostrar roles'])->name('roles');
Volt::route('permisos', 'admin.permission.index')->middleware(['auth', 'permission:mostrar permisos'])->name('permisos');
Volt::route('jugadores', 'admin.jugador.index')->middleware(['auth', 'permission:mostrar jugadores'])->name('jugadores');
Volt::route('equipos', 'admin.equipo.index')->middleware(['auth', 'permission:mostrar equipos'])->name('equipos');

require __DIR__ . '/auth.php';