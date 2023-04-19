<div class="flex items-center justify-center">
    <x-select
        option-label="name"
        option-value="value"
        :options="$styles"
        wire:model="style"
        class="w-80"
    />

    <i class="{{$style}} fa-{{$name}} w-32 !flex justify-center items-center"></i>

    <x-input type="text" class="w-60" wire:model="name" wire:keyup.debounce.500ms="searchName" list="iconOptions" />

    <datalist id="iconOptions">
        @foreach($icons as $key => $result)
            <option
                wire:key="icon-{{ $result }}"
                data-value="{{ $result }}"
                value="{{ $result }}"
            ></option>
        @endforeach
    </datalist>
</div>
