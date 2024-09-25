<?php

namespace App\Filament\Resources\TeacherUmmiResource\Pages;

use App\Filament\Resources\TeacherUmmiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTeacherUmmis extends ManageRecords
{
    protected static string $resource = TeacherUmmiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
