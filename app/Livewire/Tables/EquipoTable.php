<?php

namespace App\Livewire\Tables;

use App\Models\Equipo;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
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

final class EquipoTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'EquipoTable';
    public string $moduleName = 'equipos';
    public function open()
    {
        if (!Auth::user()->can('crear ' . $this->moduleName)) {
            return redirect()->route($this->moduleName);
        }
        $this->dispatch('createEquipo');
    }
    public function editEquipo($id)
    {
        if (!Auth::user()->can('editar ' . $this->moduleName)) {
            return redirect()->route($this->moduleName);
        }
        $this->dispatch('editEquipo', ['jugador' => Equipo::findOrFail($id)]);
    }
    public function deleteEquipo($id)
    {
        if (!Auth::user()->can('eliminar ' . $this->moduleName)) {
            return redirect()->route($this->moduleName);
        }
        $this->dispatch('deleteEquipo', ['equipo' => Equipo::findOrFail($id)]);
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
        return Equipo::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $secciones = config('admin.secciones');
        return PowerGrid::fields()
            ->add('id')
            ->add('nombre')
            ->add('seccion_id', function ($equipo) use ($secciones) {
                $seccion = collect($secciones)->firstWhere('id', $equipo->seccion_id);
                return $seccion ? $seccion['name'] : $equipo->seccion_id;
            });
    }
    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Nombre', 'nombre')
                ->sortable(),
            Column::make('Sección', 'seccion_id')
                ->sortable(),
            Column::action('Acciones')
        ];
    }
    public function filters(): array
    {
        return [
        ];
    }
    public function actions(Equipo $equipo): array
    {
        return [
            Button::add('edit')
                ->render(function ($equipo) {
                    return Blade::render(<<<HTML
                        @can('editar $this->moduleName')
                            <x-mini-button rounded icon="pencil" flat gray interaction="positive" wire:click="editEquipo('{{ $equipo->id }}')" />
                        @endcan
                    HTML);
                }),
            Button::add('delete')
                ->render(function ($equipo) {
                    return Blade::render(<<<HTML
                        @can('eliminar $this->moduleName')
                            <x-mini-button rounded icon="trash" flat gray interaction="negative" wire:click="deleteEquipo('$equipo->id')" />
                        @endcan
                    HTML);
                }),
        ];
    }
}
