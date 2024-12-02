<?php

namespace App\Filament\Resources\TeacherUmmiGradeResource\Pages;

use App\Filament\Resources\TeacherUmmiGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacherUmmiGrade extends CreateRecord
{
    protected static string $resource = TeacherUmmiGradeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
