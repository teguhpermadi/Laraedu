<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentUmmiGradeResource\Pages;
use App\Filament\Resources\StudentUmmiGradeResource\RelationManagers;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentUmmiGrade;
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

class StudentUmmiGradeResource extends Resource
{
    protected static ?string $model = StudentUmmiGrade::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('academic_year_id')
                    ->default(AcademicYear::active()->first()->id),
                Select::make('ummi_grade_id')
                    ->options(UmmiGrade::pluck('name', 'id'))
                    ->required(),
                Select::make('student_ids')
                    ->multiple()
                    ->searchable()
                    ->options(Student::whereDoesntHave('studentUmmi')->get()->pluck('name', 'id'))
                    ->live()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name'),
                TextColumn::make('ummiGrade.jilid'),
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
            'index' => Pages\ListStudentUmmiGrades::route('/'),
            'create' => Pages\CreateStudentUmmiGrade::route('/create'),
            'edit' => Pages\EditStudentUmmiGrade::route('/{record}/edit'),
        ];
    }    
}
