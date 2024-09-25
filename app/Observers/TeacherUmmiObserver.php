<?php

namespace App\Observers;

use App\Models\TeacherUmmi;

class TeacherUmmiObserver
{
    /**
     * Handle the TeacherUmmi "created" event.
     */
    public function created(TeacherUmmi $teacherUmmi): void
    {
        $teacher = $teacherUmmi->teacher_id;
        $teacher->assignRole('teacher ummi');
    }

    /**
     * Handle the TeacherUmmi "updated" event.
     */
    public function updated(TeacherUmmi $teacherUmmi): void
    {
        //
    }

    /**
     * Handle the TeacherUmmi "deleted" event.
     */
    public function deleted(TeacherUmmi $teacherUmmi): void
    {
        $teacher = $teacherUmmi->teacher_id;
        $teacher->removeRole('teacher ummi');
    }

    /**
     * Handle the TeacherUmmi "restored" event.
     */
    public function restored(TeacherUmmi $teacherUmmi): void
    {
        //
    }

    /**
     * Handle the TeacherUmmi "force deleted" event.
     */
    public function forceDeleted(TeacherUmmi $teacherUmmi): void
    {
        //
    }
}
