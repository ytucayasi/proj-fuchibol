<?php

namespace App\Livewire\Forms\Admin;

use App\Models\Equipo;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EquipoForm extends Form
{
    public ?Equipo $equipo = null;
    public ?int $id = null;
    public ?string $nombre = "";
    public ?int $seccion_id = null;
    public function mount()
    {
        $this->equipo = new Equipo();
    }
    public function rules()
    {
        return [
            'nombre' => 'required|string|max:150',
            'seccion_id' => 'required|integer'
        ];
    }
    public function setEquipo(Equipo $equipo)
    {
        $this->equipo = $equipo;
        $this->id = $equipo->id;
        $this->nombre = $equipo->nombre;
        $this->seccion_id = $equipo->seccion_id;
    }
    public function store()
    {
        Equipo::create($this->only('nombre', 'seccion_id'));
    }
    public function update()
    {
        $this->equipo->update($this->only('nombre', 'seccion_id'));
    }
    public function delete()
    {
        $this->equipo->delete();
    }
}