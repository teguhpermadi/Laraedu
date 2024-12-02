<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherUmmiGradeResource\Pages;
use App\Filament\Resources\TeacherUmmiGradeResource\RelationManagers;
use App\Models\AcademicYear;
use App\Models\TeacherUmmi;
use App\Models\TeacherUmmiGrade;
use App\Models\UmmiGrade;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeacherUmmiGradeResource extends Resource
{
    protected static ?string $model = TeacherUmmiGrade::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('academic_year_id')->default(AcademicYear::active()->first()->id),
                Select::make('teacher_ummi_id')
                    ->options(TeacherUmmi::with('teacher')->get()->pluck('teacher.name', 'id'))
                    ->required(),
                Select::make('ummi_grade_id')
                    ->options(UmmiGrade::pluck('name', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacherUmmi.teacher.name'),
                TextColumn::make('ummiGrade.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeacherUmmiGrades::route('/'),
            'create' => Pages\CreateTeacherUmmiGrade::route('/create'),
            'edit' => Pages\EditTeacherUmmiGrade::route('/{record}/edit'),
        ];
    }    
}
