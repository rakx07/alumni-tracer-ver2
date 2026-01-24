@props(['label','value'=>null,'block'=>false])

<div class="bg-gray-50 border rounded p-4 {{ $block ? 'md:col-span-2' : '' }}">
    <div class="text-gray-500 text-xs font-medium">{{ $label }}</div>
    <div class="font-semibold text-gray-900 whitespace-pre-line">
        {{ filled($value) ? $value : '—' }}
    </div>
</div>
