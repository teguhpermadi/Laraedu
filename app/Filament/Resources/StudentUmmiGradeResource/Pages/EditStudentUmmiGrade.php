<?php

namespace App\Filament\Resources\StudentUmmiGradeResource\Pages;

use App\Filament\Resources\StudentUmmiGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentUmmiGrade extends EditRecord
{
    protected static string $resource = StudentUmmiGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
