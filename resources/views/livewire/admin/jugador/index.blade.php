<?php

use App\Livewire\Forms\Admin\JugadorForm;
use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;
    public JugadorForm $jugadorForm;
    public string $modelName = 'Jugador';
    public bool $modal = false;
    public bool $modalDelete = false;
    public $equipos = [];
    public $users = [];

    public function mount()
    {
        $this->equipos = Equipo::all()->map(function ($equipo) {
            return [
                'id' => $equipo->id,
                'name' => $equipo->nombre,
            ];
        });
        $this->users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });
    }

    #[On('createJugador')]
    public function open()
    {
        $this->resetForm();
        $this->modal = true;
    }

    #[On('editJugador')]
    public function setJugador(Jugador $jugador)
    {
        $this->resetForm();
        $this->jugadorForm->setJugador($jugador);
        $this->modal = true;
    }

    #[On('deleteJugador')]
    public function removeJugador(Jugador $jugador)
    {
        $this->jugadorForm->setJugador($jugador);
        $this->modalDelete = true;
    }

    public function delete()
    {
        $this->jugadorForm->delete();
        $this->dispatch('pg:eventRefresh-JugadorTable');
        $this->modalDelete = false;
    }

    public function clear()
    {
        $this->resetForm();
        $this->dispatch('clear-file-input');
    }

    public function save()
    {
        $this->jugadorForm->id
            ? $this->update()
            : $this->store();
    }

    public function validateForm()
    {
        $this->jugadorForm->validate();
    }

    public function store()
    {
        $this->validateForm();
        $this->jugadorForm->store();
        $this->resetForm();
        $this->dispatch('pg:eventRefresh-JugadorTable');
        $this->modal = false;

    }

    public function update()
    {
        $this->validateForm();
        $this->jugadorForm->update();
        $this->dispatch('pg:eventRefresh-JugadorTable');
        $this->modal = false;
    }

    public function resetForm()
    {
        $this->jugadorForm->resetValidation();
        $this->jugadorForm->reset();
        $this->dispatch('clear-file-input');
    }

    public function check()
    {
        if (!Auth::user()->can('mostrar jugadores')) {
            return redirect()->route('dashboard');
        }
    }
}; ?>

<div>
    <livewire:tables.jugador-table />
    <x-modal wire:model="modalDelete" width="sm">
        <x-card>
            <div class="flex flex-col justify-center items-center gap-4">
                <div class="bg-warning-400 dark:border-4 dark rounded-full p-4">
                    <x-phosphor.icons::regular.warning class="text-white w-16 h-16" />
                </div>
                <span class="text-center font-semibold text-xl">¿Desea eliminar el jugador?</span>
                <span class="text-center">Recuerda que el jugador no tendrá acceso al sistema</span>
                <div class="flex gap-2">
                    <x-button flat label="Cancelar" x-on:click="close" />
                    <x-button flat negative label="Eliminar" wire:click="delete" />
                </div>
            </div>
        </x-card>
    </x-modal>

    <x-modal-card title="{{ ($jugadorForm->id ? 'Editar' : 'Registrar') . ' ' . $modelName }}" wire:model="modal"
        width="sm">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">

            <!-- Número de Colegiatura -->
            <x-maskable label="Número de Colegiatura" placeholder="Ingresar"
                wire:model.live="jugadorForm.nro_colegiatura" mask="########" />

            <!-- Usuario -->
            <x-select label="Usuario" placeholder="Seleccionar" :options="$this->users" option-label="name"
                option-value="id" wire:model="jugadorForm.user_id" />

            <!-- Tipo de Jugador -->
            <x-select label="Equipo" placeholder="Seleccionar" :options="$this->equipos" option-label="name"
                option-value="id" wire:model="jugadorForm.equipo_id" />

            <!-- Tipo de Jugador -->
            <x-select label="Tipo de Jugador" placeholder="Seleccionar" :options="[['name' => 'Normal', 'id' => 1], ['name' => 'Presidente', 'id' => 2]]" option-label="name" option-value="id"
                wire:model="jugadorForm.tipo_jugador" />

            <!-- Linea -->
            <hr class="md:col-span-1 my-2 border-slate-300 dark:border-slate-600" />

            <!-- Foto de Perfil -->
            <x-input-file label="Foto Perfil" wireModel="jugadorForm.foto_perfil" />

            <!-- Documento de Identidad -->
            <x-input-file label="Documento DNI" wireModel="jugadorForm.doc_dni" />

            <!-- Titulo  -->
            <x-input-file label="Documento Título" wireModel="jugadorForm.doc_titulo" />

            <!-- Colegiatura -->
            <x-input-file label="Documento Colegiatura" wireModel="jugadorForm.doc_colegiatura" />
        </div>
        <x-slot name="footer" class="flex justify-between items-center gap-x-4">

            <!-- Botón de Eliminar -->
            <x-mini-button flat negative rounded icon="trash" wire:click="clear" />
            <div class="flex gap-x-2">

                <!-- Botón de Cancelar -->
                <x-button flat label="Cancelar" x-on:click="close" />

                <!-- Botón de Guardar -->
                <x-button flat positive label="Guardar" wire:click="save" />
            </div>
        </x-slot>
    </x-modal-card>
</div>