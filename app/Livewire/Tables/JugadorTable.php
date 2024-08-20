<?php

namespace App\Livewire\Tables;

use App\Models\Jugador;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class JugadorTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'JugadorTable';
    public string $moduleName = 'jugadores';
    public function open()
    {
        if (!Auth::user()->can('crear ' . $this->moduleName)) {
            return redirect()->route($this->moduleName);
        }
        $this->dispatch('createJugador');
    }
    public function editJugador($id)
    {
        if (!Auth::user()->can('editar ' . $this->moduleName)) {
            return redirect()->route($this->moduleName);
        }
        $this->dispatch('editJugador', ['jugador' => Jugador::findOrFail($id)]);
    }
    public function deleteJugador($id)
    {
        if (!Auth::user()->can('eliminar ' . $this->moduleName)) {
            return redirect()->route($this->moduleName);
        }
        $this->dispatch('deleteJugador', ['jugador' => Jugador::findOrFail($id)]);
    }
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput()->includeViewOnTop('components.datatable.header-top'),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Jugador::query()->with('user.persona');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nombres_apellidos', fn($jugador) => e($jugador->user->persona->nombre . ' ' . $jugador->user->persona->apellido_paterno . ' ' . $jugador->user->persona->apellido_materno))
            ->add('dni', fn($jugador) => e($jugador->user->persona->dni))
            ->add('nro_colegiatura', fn($jugador) => e($jugador->nro_colegiatura))
            ->add('equipo');
    }

    public function relationSearch(): array
    {
        return [
            'user.persona' => [
                'name',
                'dni',
                'nombre',
                'apellido_paterno',
                'apellido_materno'
            ],
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),
            Column::make('Nombres y Apellidos', 'nombres_apellidos', 'nombre'),
            Column::make('Nro. Colegiatura', 'nro_colegiatura')
                ->searchable(),
            Column::make('DNI', 'dni', 'persona.dni')
                ->searchable(),
            Column::make('Equipo', 'equipo')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }
}
