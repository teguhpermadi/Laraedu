<?php

namespace App\Filament\Resources\UmmiGradeResource\Pages;

use App\Filament\Resources\UmmiGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUmmiGrades extends ManageRecords
{
    protected static string $resource = UmmiGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
