<?php

namespace App\Observers;

use App\Models\Student;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        $student->dataStudent()->update(['student_ulid' => $student->ulid]);
        $student->studentGrade()->update(['student_ulid' => $student->ulid]);
        $student->studentCompetency()->update(['student_ulid' => $student->ulid]);
        $student->exam()->update(['student_ulid' => $student->ulid]);
        $student->attendance()->update(['student_ulid' => $student->ulid]);
        $student->extracurricular()->update(['student_ulid' => $student->ulid]);
        $student->attitude()->update(['student_ulid' => $student->ulid]);
        $student->project()->update(['student_ulid' => $student->ulid]);
        $student->projectNote()->update(['student_ulid' => $student->ulid]);
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        //
    }
}
