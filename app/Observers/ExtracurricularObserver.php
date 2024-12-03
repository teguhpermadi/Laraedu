<?php

namespace App\Observers;

use App\Models\Extracurricular;

class ExtracurricularObserver
{
    /**
     * Handle the Extracurricular "created" event.
     */
    public function created(Extracurricular $extracurricular): void
    {
        //
    }

    /**
     * Handle the Extracurricular "updated" event.
     */
    public function updated(Extracurricular $extracurricular): void
    {
        $extracurricular->studentExtracurricular()->update(['extracurricular_ulid' => $extracurricular->ulid]);
        $extracurricular->teacherExtracurricular()->update(['extracurricular_ulid' => $extracurricular->ulid]);
    }

    /**
     * Handle the Extracurricular "deleted" event.
     */
    public function deleted(Extracurricular $extracurricular): void
    {
        //
    }

    /**
     * Handle the Extracurricular "restored" event.
     */
    public function restored(Extracurricular $extracurricular): void
    {
        //
    }

    /**
     * Handle the Extracurricular "force deleted" event.
     */
    public function forceDeleted(Extracurricular $extracurricular): void
    {
        //
    }
}
