<?php

use App\Livewire\Forms\Admin\EquipoForm;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public EquipoForm $equipoForm;
    public string $modelName = 'Equipo';
    public bool $modal = false;
    public bool $modalDelete = false;
    #[On('createEquipo')]
    public function open()
    {
        $this->resetForm();
        $this->modal = true;
    }
    #[On('editEquipo')]
    public function setEquipo(Equipo $equipo)
    {
        $this->resetForm();
        $this->equipoForm->setEquipo($equipo);
        $this->modal = true;
    }
    #[On('deleteEquipo')]
    public function removeEquipo(Equipo $equipo)
    {
        $this->equipoForm->setEquipo($equipo);
        $this->modalDelete = true;
    }
    public function delete()
    {
        $this->equipoForm->delete();
        $this->dispatch('pg:eventRefresh-EquipoTable');
        $this->modalDelete = false;
    }
    public function clear()
    {
        $this->resetForm();
    }
    public function save()
    {
        $this->equipoForm->id
            ? $this->update()
            : $this->store();
    }
    public function validateForm()
    {
        $this->equipoForm->validate();
    }
    public function store()
    {
        $this->validateForm();
        $this->equipoForm->store();
        $this->resetForm();
        $this->dispatch('pg:eventRefresh-EquipoTable');
        $this->modal = false;
    }
    public function update()
    {
        $this->validateForm();
        $this->equipoForm->update();
        $this->dispatch('pg:eventRefresh-EquipoTable');
        $this->modal = false;
    }
    public function resetForm()
    {
        $this->equipoForm->resetValidation();
        $this->equipoForm->reset();
    }
    public function check()
    {
        if (!Auth::user()->can('mostrar equipos')) {
            return redirect()->route('dashboard');
        }
    }
}; ?>
<div wire:poll="check">
    <livewire:tables.equipo-table />
    <x-modal wire:model="modalDelete" width="sm">
        <x-card>
            <div class="flex flex-col justify-center items-center gap-4">
                <div class="bg-warning-400 dark:border-4 dark rounded-full p-4">
                    <x-phosphor.icons::regular.warning class="text-white w-16 h-16" />
                </div>
                <span class="text-center font-semibold text-xl">¿Desea eliminar el rol?</span>
                <span class="text-center">Recuerde que se eliminará definitivamente</span>
                <div class="flex gap-2">
                    <x-button flat label="Cancelar" x-on:click="close" />
                    <x-button flat negative label="Eliminar" wire:click="delete" />
                </div>
            </div>
        </x-card>
    </x-modal>
    <x-modal-card title="{{($equipoForm->id ? 'Editar' : 'Registrar') . ' ' . $modelName}}" wire:model="modal" width="sm">
        <div class="grid grid-cols-1 gap-6">
            <!-- Nombre -->
            <x-input label="Nombre" placeholder="Ingresar" wire:model="equipoForm.nombre" class="w-10" />
            <x-select class="gap-0" label="Permiso" placeholder="Seleccionar" :options="config('admin.secciones')"
                option-label="name" option-value="id" wire:model.live="equipoForm.seccion_id">
            </x-select>
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