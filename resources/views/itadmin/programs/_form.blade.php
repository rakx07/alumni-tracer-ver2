@php
    $p = $program ?? null;
@endphp

<div class="space-y-3">

    <div>
        <label class="block text-sm font-medium">Category</label>
        <select name="category" class="w-full border rounded p-2" required>
            <option value="college" {{ old('category', $p->category ?? '')==='college'?'selected':'' }}>College</option>
            <option value="grad_school" {{ old('category', $p->category ?? '')==='grad_school'?'selected':'' }}>Graduate School</option>
            <option value="law" {{ old('category', $p->category ?? '')==='law'?'selected':'' }}>Law</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium">Code (optional)</label>
        <input name="code" class="w-full border rounded p-2"
               value="{{ old('code', $p->code ?? '') }}"
               placeholder="e.g. BSIT, MBA, JD">
    </div>

    <div>
        <label class="block text-sm font-medium">Program Name</label>
        <input name="name" class="w-full border rounded p-2"
               value="{{ old('name', $p->name ?? '') }}"
               placeholder="e.g. Bachelor of Science in Information Technology"
               required>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1"
               {{ old('is_active', $p->is_active ?? true) ? 'checked' : '' }}>
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
