<?php

namespace App\Filament\Resources\StudentUmmiGradeResource\Pages;

use App\Filament\Resources\StudentUmmiGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentUmmiGrades extends ListRecords
{
    protected static string $resource = StudentUmmiGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
