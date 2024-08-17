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
Volt::route('jugadores', 'admin.permission.index')->middleware(['auth', 'permission:mostrar permisos'])->name('jugadores');
Volt::route('presidentes', 'admin.permission.index')->middleware(['auth', 'permission:mostrar permisos'])->name('presidentes');
Volt::route('equipos', 'admin.permission.index')->middleware(['auth', 'permission:mostrar permisos'])->name('equipos');
Volt::route('secciones', 'admin.permission.index')->middleware(['auth', 'permission:mostrar permisos'])->name('secciones');

require __DIR__ . '/auth.php';