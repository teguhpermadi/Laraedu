<?php

namespace App\Filament\Resources\StudentUmmiGradeResource\Pages;

use App\Filament\Resources\StudentUmmiGradeResource;
use App\Models\AcademicYear;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateStudentUmmiGrade extends CreateRecord
{
    protected static string $resource = StudentUmmiGradeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $student_grade = [];
        foreach ($data['student_ids'] as $student_id) {
            $student_grade = [
                'academic_year_id' => AcademicYear::active()->first()->id,
                'ummi_grade_id' => $data['ummi_grade_id'],
                'student_id' => $student_id,
            ];

            static::getModel()::updateOrCreate($student_grade);
        }
        // dd($student_grade);
        return static::getModel()::updateOrCreate($student_grade);
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
