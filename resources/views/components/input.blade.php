@props([
    'label' => null,
    'id' => null,
    'type' => 'text',
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'error' => null,
    'helper' => null,
])

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-xs font-bold uppercase tracking-wider text-[#A3A3A3]">
            {{ $label }} @if($required) <span class="text-[#D4AF37]">*</span> @endif
        </label>
    @endif

    <input 
        id="{{ $id ?? $name }}" 
        type="{{ $type }}" 
        name="{{ $name }}" 
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}" 
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full px-3.5 py-2.5 bg-[#0B0B0B] border border-[#2A2A2A] text-white placeholder-[#737373] rounded-xl text-xs sm:text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] outline-none transition-all ' . ($error ? 'border-rose-500' : '')]) }}>

    @if($helper)
        <p class="text-[11px] text-[#737373]">{{ $helper }}</p>
    @endif

    @if($error)
        <p class="text-xs text-rose-400 mt-1">{{ $error }}</p>
    @endif
</div>
