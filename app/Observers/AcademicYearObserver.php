<?php

namespace App\Observers;

use App\Models\AcademicYear;

class AcademicYearObserver
{
    /**
     * Handle the AcademicYear "created" event.
     */
    public function created(AcademicYear $academicYear): void
    {
        //
    }

    /**
     * Handle the AcademicYear "updated" event.
     */
    public function updated(AcademicYear $academicYear): void
    {
        $academicYear->studentGrade()->update(['academic_year_ulid' => $academicYear->ulid]);
        $academicYear->teacherGrade()->update(['academic_year_ulid' => $academicYear->ulid]);
        $academicYear->teacherSubject()->update(['academic_year_ulid' => $academicYear->ulid]);
        $academicYear->teacherExtracurricular()->update(['academic_year_ulid' => $academicYear->ulid]);
        $academicYear->studentExtracurricular()->update(['academic_year_ulid' => $academicYear->ulid]);
        $academicYear->project()->update(['academic_year_ulid' => $academicYear->ulid]);
        $academicYear->projectCoordinator()->update(['academic_year_ulid' => $academicYear->ulid]);
        $academicYear->projectNote()->update(['academic_year_ulid' => $academicYear->ulid]);
        $academicYear->projectStudent()->update(['academic_year_ulid' => $academicYear->ulid]);
        $academicYear->attendance()->update(['academic_year_ulid' => $academicYear->ulid]);
        $academicYear->attitude()->update(['academic_year_ulid' => $academicYear->ulid]);
    }

    /**
     * Handle the AcademicYear "deleted" event.
     */
    public function deleted(AcademicYear $academicYear): void
    {
        //
    }

    /**
     * Handle the AcademicYear "restored" event.
     */
    public function restored(AcademicYear $academicYear): void
    {
        //
    }

    /**
     * Handle the AcademicYear "force deleted" event.
     */
    public function forceDeleted(AcademicYear $academicYear): void
    {
        //
    }
}
