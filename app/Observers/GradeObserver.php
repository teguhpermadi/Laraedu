<?php

namespace App\Observers;

use App\Models\Grade;

class GradeObserver
{
    /**
     * Handle the Grade "created" event.
     */
    public function created(Grade $grade): void
    {
        //
    }

    /**
     * Handle the Grade "updated" event.
     */
    public function updated(Grade $grade): void
    {
        $grade->teacherSubject()->update(['grade_ulid' => $grade->ulid]);
        $grade->teacherGrade()->update(['grade_ulid' => $grade->ulid]);
        $grade->studentGrade()->update(['grade_ulid' => $grade->ulid]);
        $grade->attitude()->update(['grade_ulid' => $grade->ulid]);
        $grade->project()->update(['grade_ulid' => $grade->ulid]);
        $grade->projectCoordinator()->update(['grade_ulid' => $grade->ulid]);
        $grade->attendance()->update(['grade_ulid' => $grade->ulid]);
    }

    /**
     * Handle the Grade "deleted" event.
     */
    public function deleted(Grade $grade): void
    {
        //
    }

    /**
     * Handle the Grade "restored" event.
     */
    public function restored(Grade $grade): void
    {
        //
    }

    /**
     * Handle the Grade "force deleted" event.
     */
    public function forceDeleted(Grade $grade): void
    {
        //
    }
}
