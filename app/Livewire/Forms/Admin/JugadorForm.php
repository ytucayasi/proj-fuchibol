<?php

namespace App\Livewire\Forms\Admin;

use App\Models\Jugador;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;
use Livewire\WithFileUploads;
class JugadorForm extends Form
{
    use WithFileUploads;
    public ?Jugador $jugador;
    public ?int $id = null;
    public ?int $equipo_id = null;
    public ?int $user_id = null;
    public ?string $nro_colegiatura = "";
    public ?int $tipo_jugador = 1;

    /* Documentos */
    public $foto_perfil; // Documento 1
    public $doc_dni; // Documento 2
    public $doc_titulo; // Documento 3
    public $doc_colegiatura; // Documento 4
    public function mount()
    {
        $this->jugador = new Jugador();
    }
    public function rules()
    {
        return [
            'equipo_id' => "required|integer|exists:equipos,id",
            'user_id' => "required|integer|exists:users,id",
            'nro_colegiatura' => 'required|integer|min:10000000',
            'tipo_jugador' => 'required|integer',
            'foto_perfil' => 'nullable|file|max:2024|mimes:jpeg,jpg,png', // Permite imágenes y PDFs
            'doc_dni' => 'nullable|file|max:2024|mimes:pdf', // Solo permite PDFs
            'doc_titulo' => 'nullable|file|max:2024|mimes:pdf', // Solo permite PDFs
            'doc_colegiatura' => 'nullable|file|max:2024|mimes:pdf', // Solo permite PDFs
        ];
    }
    public function setJugador(Jugador $jugador)
    {
        $this->jugador = $jugador;
        $this->id = $jugador->id;
        $this->equipo_id = $jugador->equipo_id;
        $this->user_id = $jugador->user_id;
        $this->nro_colegiatura = $jugador->nro_colegiatura;
        $this->tipo_jugador = $jugador->tipo_jugador;
        $this->foto_perfil = $jugador->foto_perfil;
        $this->doc_dni = $jugador->doc_dni;
        $this->doc_titulo = $jugador->doc_titulo;
        $this->doc_colegiatura = $jugador->doc_colegiatura;
    }
    public function store()
    {
        // Crear carpeta para los documentos
        $folderPath = 'jugadores/' . $this->id;
        Storage::makeDirectory($folderPath);
        $this->foto_perfil = $this->foto_perfil ? $this->saveFile($this->foto_perfil, "foto_perfil_{$this->id}", $folderPath) : null;
        $this->doc_dni = $this->doc_dni ? $this->saveFile($this->doc_dni, "doc_dni_{$this->id}", $folderPath) : null;
        $this->doc_titulo = $this->doc_titulo ? $this->saveFile($this->doc_titulo, "doc_titulo_{$this->id}", $folderPath) : null;
        $this->doc_colegiatura = $this->doc_colegiatura ? $this->saveFile($this->doc_colegiatura, "doc_colegiatura_{$this->id}", $folderPath) : null;

        Jugador::create($this->only([
            'id',
            'equipo_id',
            'user_id',
            'nro_colegiatura',
            'tipo_jugador',
            'foto_perfil',
            'doc_dni',
            'doc_titulo',
            'doc_colegiatura'
        ]));
    }

    private function saveFile($file, $filename, $folderPath)
    {
        if ($file) {
            $extension = $file->extension();
            $fullFilename = "$filename.$extension";
            $path = "$folderPath/$fullFilename";
            $file->storeAs($folderPath, $fullFilename);
            return $path;
        }
        return null;
    }

    public function update()
    {
        $folderPath = 'jugadores/' . $this->id;

        // Verificar si hay documentos nuevos y reemplazarlos
        if ($this->foto_perfil) {
            Storage::delete($this->jugador->foto_perfil);
            $this->jugador->foto_perfil = $this->saveFile($this->foto_perfil, "foto_perfil_{$this->id}", $folderPath);
        }

        if ($this->doc_dni) {
            Storage::delete($this->jugador->doc_dni);
            $this->jugador->doc_dni = $this->saveFile($this->doc_dni, "doc_dni_{$this->id}", $folderPath);
        }

        if ($this->doc_titulo) {
            Storage::delete($this->jugador->doc_titulo);
            $this->jugador->doc_titulo = $this->saveFile($this->doc_titulo, "doc_titulo_{$this->id}", $folderPath);
        }

        if ($this->doc_colegiatura) {
            Storage::delete($this->jugador->doc_colegiatura);
            $this->jugador->doc_colegiatura = $this->saveFile($this->doc_colegiatura, "doc_colegiatura_{$this->id}", $folderPath);
        }

        $this->jugador->update($this->only([
            'equipo_id',
            'user_id',
            'nro_colegiatura',
            'tipo_jugador',
            'foto_perfil',
            'doc_dni',
            'doc_titulo',
            'doc_colegiatura'
        ]));
    }

    public function delete()
    {
        // Eliminar los documentos y la carpeta
        $folderPath = 'jugadores/' . $this->jugador->dni;
        Storage::deleteDirectory($folderPath);

        $this->jugador->delete();
    }
}
