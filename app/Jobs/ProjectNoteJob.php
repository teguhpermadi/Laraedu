<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\ProjectNote;
use App\Models\StudentGrade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProjectNoteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $projects = Project::all();
        foreach ($projects as $project) {
            $students = StudentGrade::where('grade_id', $project->grade_id)->get();
            
            foreach ($students as $student) {
                $data = [
                    'academic_year_id' => $project->academic_year_id,
                    'student_id' => $student->student_id,
                    'project_id' => $project->id,
                    'note' => '-',
                ];
                
                ProjectNote::updateOrCreate($data, [
                    'project_id' => $project->id
                ]);
            }

        }
    }
}
