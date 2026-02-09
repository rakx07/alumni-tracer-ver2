<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Alumni Record</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h2 { margin-bottom: 5px; }
        h3 { margin-top: 18px; border-bottom: 1px solid #000; }
        .row { margin-bottom: 6px; }
        .label { font-weight: bold; }
        .box { border: 1px solid #ccc; padding: 8px; margin-bottom: 8px; }
    </style>
</head>
<body>

<h2>Alumni Record</h2>

<div class="row"><span class="label">Full Name:</span> {{ $alumnus->full_name }}</div>
<div class="row"><span class="label">Email:</span> {{ $alumnus->email }}</div>

<h3>Academic Background</h3>

@foreach($alumnus->educations as $e)
    <div class="box">
        <div><strong>Level:</strong> {{ strtoupper(str_replace('_',' ', $e->level)) }}</div>
        <div><strong>Graduated:</strong>
            {{ is_null($e->did_graduate) ? '—' : ($e->did_graduate ? 'Yes' : 'No') }}
        </div>

        <div><strong>Year Entered:</strong> {{ $e->year_entered ?? '—' }}</div>

        @if($e->did_graduate)
            <div><strong>Year Graduated:</strong> {{ $e->year_graduated ?? '—' }}</div>
        @else
            <div><strong>Last School Year Attended:</strong> {{ $e->last_year_attended ?? '—' }}</div>
        @endif

        @if($e->strand)
            <div><strong>Strand:</strong>
                {{ $e->strand->code }} — {{ $e->strand->name }}
            </div>
        @elseif($e->strand_track)
            <div><strong>Strand:</strong> {{ $e->strand_track }}</div>
        @endif

        @if($e->program)
            <div><strong>Program:</strong>
                {{ $e->program->code ? $e->program->code.' — ' : '' }}{{ $e->program->name }}
            </div>
        @elseif($e->specific_program)
            <div><strong>Program:</strong> {{ $e->specific_program }} (Others)</div>
        @elseif($e->degree_program)
            <div><strong>Program:</strong> {{ $e->degree_program }}</div>
        @endif
    </div>
@endforeach

</body>
</html>
