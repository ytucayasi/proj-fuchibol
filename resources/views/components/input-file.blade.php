<div x-data="{ fileName: '', fileSize: '', fileExtension: '', fileSelected: false }" wire:ignore
    @clear-file-input.window="fileName = ''; fileSize = ''; fileExtension = ''; fileSelected = false;">
    <label :for="$id('file')" class="block text-sm font-medium mb-2">
        {{ $label }}
    </label>

    <button type="button" @click.prevent="$refs.fileInput.click()"
        class="py-2 px-4 w-full bg-primary-600 text-white rounded-lg shadow-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 mb-2">
        Haga click para subir un archivo
    </button>

    <input type="file" x-ref="fileInput" wire:model="{{ $wireModel }}" :id="$id('file')" 
        @change="if ($refs.fileInput.files.length > 0) {
                    let file = $refs.fileInput.files[0];
                    fileName = file.name.length > 20 ? file.name.slice(0, 20) + '...' : file.name;
                    fileSize = (file.size / 1024).toFixed(2) + ' KB';
                    fileExtension = file.name.split('.').pop().toUpperCase();
                    fileSelected = true; 
                } else {
                    fileName = ''; 
                    fileSize = ''; 
                    fileExtension = ''; 
                    fileSelected = false;
                }" class="hidden" />

    <div x-show="fileSelected" class="flex items-center justify-between mt-2 text-gray-500">
        <div>
            <span x-text="fileName" class="text-sm"></span>
            <span x-text="fileExtension" class="text-sm text-gray-400 ml-2"></span>
        </div>
        <button type="button"
            @click="fileName = ''; fileSize = ''; fileExtension = ''; fileSelected = false; $refs.fileInput.value = ''; @this.set('{{ $wireModel }}', null)"
            class="text-negative-600 hover:text-negative-800 ml-2">
            <x-icon name="x-mark" />
        </button>
    </div>

    <div x-show="fileSelected" class="text-sm text-gray-500 mt-2">
        <span x-text="fileSize"></span>
    </div>

    <div wire:loading wire:target="{{ $wireModel }}" class="text-sm text-gray-500 mt-2">
        Subiendo archivo...
    </div>
</div>

@error($wireModel)
    <div class="text-sm text-negative-600 mt-2">
        {{ $message }}
    </div>
@enderror
