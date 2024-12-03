<?php

namespace App\Observers;

use App\Models\Exam;
use App\Models\TeacherSubject;

class TeacherSubjectObserver
{
    /**
     * Handle the TeacherSubject "created" event.
     */
    public function created(TeacherSubject $teacherSubject): void
    {
        $data = [];
        
        $students = $teacherSubject->studentGrade;

        foreach ($students as $student) {
            $data[] = [
                'teacher_subject_id' => $teacherSubject->id,
                'student_id' => $student->student_id,
            ];
        }

        Exam::insert($data);
    }

    /**
     * Handle the TeacherSubject "updated" event.
     */
    public function updated(TeacherSubject $teacherSubject): void
    {
        $teacherSubject->exam()->update(['teacher_subject_ulid' => $teacherSubject->ulid]);
        $teacherSubject->studentCompetency()->update(['teacher_subject_ulid' => $teacherSubject->ulid]);
        $teacherSubject->competencies()->update(['teacher_subject_ulid' => $teacherSubject->ulid]);
    }

    /**
     * Handle the TeacherSubject "deleted" event.
     */
    public function deleted(TeacherSubject $teacherSubject): void
    {
        //
    }

    /**
     * Handle the TeacherSubject "restored" event.
     */
    public function restored(TeacherSubject $teacherSubject): void
    {
        //
    }

    /**
     * Handle the TeacherSubject "force deleted" event.
     */
    public function forceDeleted(TeacherSubject $teacherSubject): void
    {
        //
    }
}
