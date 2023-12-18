<?php

namespace App\Observers;

use App\Models\AcademicYear;
use App\Models\Project;
use App\Models\ProjectNote;
use App\Models\StudentGrade;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $academic = AcademicYear::active()->first()->id;
        $students = StudentGrade::where('grade_id', $project->grade_id)->get();

        $data = [];
        foreach ($students as $student) {
            $data[] = [
                'academic_year_id' => $academic,
                'student_id' => $student->id,
                'project_id' => $project->id,
                'note' => '-',
            ];
        }

        ProjectNote::insert($data);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
