@php
    $s = $strand ?? null;
@endphp

<div class="space-y-3">

    <div>
        <label class="block text-sm font-medium">Code</label>
        <input name="code" class="w-full border rounded p-2"
               value="{{ old('code', $s->code ?? '') }}"
               placeholder="e.g. STEM, HUMSS, ABM" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Name</label>
        <input name="name" class="w-full border rounded p-2"
               value="{{ old('name', $s->name ?? '') }}"
               placeholder="Full strand name" required>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1"
               {{ old('is_active', $s->is_active ?? true) ? 'checked' : '' }}>
        <label class="text-sm">Active</label>
    </div>

    @if ($errors->any())
        <div class="p-3 bg-red-100 border border-red-300 rounded text-sm">
            <div class="font-semibold mb-1">Please fix the following:</div>
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
