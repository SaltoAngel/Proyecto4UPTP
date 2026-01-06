@props(['name', 'type' => 'text', 'label', 'required' => false, 'placeholder' => '', 'value' => ''])

<div class="form-group">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input 
        type="{{ $type }}" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        class="form-control" 
        value="{{ old($name, $value) }}" 
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes }}
    >
    <span class="validation-feedback validation-error" style="display: none;"></span>
</div>