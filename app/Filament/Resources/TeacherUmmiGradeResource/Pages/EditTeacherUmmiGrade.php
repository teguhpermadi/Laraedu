<?php

namespace App\Filament\Resources\TeacherUmmiGradeResource\Pages;

use App\Filament\Resources\TeacherUmmiGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeacherUmmiGrade extends EditRecord
{
    protected static string $resource = TeacherUmmiGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
