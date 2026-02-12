<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniEducation extends Model
{
    // force the correct table
    protected $table = 'alumni_educations';

    protected $fillable = [
        'alumnus_id',
        'level',

        // NEW (requirements-based)
        'did_graduate',

        'student_number',
        'year_entered',
        'year_graduated',
        'last_year_attended',

        // Programs (pre-listed + others)
        'program_id',
        'specific_program',   // used when "Others" is selected

        // Legacy text fields (kept for compatibility)
        'degree_program',
        'research_title',
        'thesis_title',

        // Strands
        'strand_id',
        'strand_track',

        'honors_awards',
        'extracurricular_activities',
        'clubs_organizations',

        // Post-NDMU
        'institution_name',
        'institution_address',
        'course_degree',
        'year_completed',
        'scholarship_award',
        'notes',
    ];

    protected $casts = [
         'did_graduate' => 'integer', // important (0/1/null)
        'year_entered' => 'integer',
        'year_graduated' => 'integer',
        'last_year_attended' => 'integer',
        'year_completed' => 'integer',
    ];

    /* =========================
     |  Relationships
     ========================= */
    public function alumnus()
    {
        return $this->belongsTo(Alumnus::class, 'alumnus_id');
    }

    public function strand()
    {
        return $this->belongsTo(Strand::class, 'strand_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /* =========================
     |  Helpers (optional)
     ========================= */

    // Display-friendly program label (handles Others)
    public function programLabel(): ?string
    {
        if ($this->program) {
            return trim(($this->program->code ? $this->program->code.' — ' : '').$this->program->name);
        }

        if (!empty($this->specific_program)) {
            return $this->specific_program;
        }

        return null;
    }

    public function strandLabel(): ?string
    {
        if ($this->strand) {
            return $this->strand->code.' — '.$this->strand->name;
        }

        return $this->strand_track ?: null;
    }
}
