@props(['label', 'name', 'hint' => null])

<div class="field">
    <label for="{{ $name }}">{{ $label }}</label>
    {{ $slot }}
    @if ($hint)
        <p class="hint">{{ $hint }}</p>
    @endif
    @error($name)
        <p class="error">{{ $message }}</p>
    @enderror
</div>
