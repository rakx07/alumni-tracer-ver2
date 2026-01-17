<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniEducation extends Model
{
    // ✅ force the correct table
    protected $table = 'alumni_educations';

    protected $fillable = [
        'alumnus_id',
        'level',
        'student_number',
        'year_entered',
        'year_graduated',
        'last_year_attended',
        'degree_program',
        'specific_program',
        'research_title',
        'thesis_title',
        'strand_track',
        'honors_awards',
        'extracurricular_activities',
        'clubs_organizations',
        'institution_name',
        'institution_address',
        'course_degree',
        'year_completed',
        'scholarship_award',
        'notes',
    ];

    public function alumnus()
    {
        return $this->belongsTo(Alumnus::class, 'alumnus_id');
    }
}
