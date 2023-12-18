<?php

namespace App\Filament\Pages;

use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\TeacherGrade;
use Filament\Tables\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class MyGrade extends Page implements HasTable
{
    use HasPageShield;
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Report';

    protected static ?string $navigationGroup = 'Teacher Grade';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.my-grade';

    public $visible = false;

    public function mount()
    {
        $data = TeacherGrade::where('teacher_id', auth()->user()->userable->user_id)->first();
        
        if($data->curriculum == 'merdeka'){
            $this->visible = true;
        }
    }

    // public static function shouldRegisterNavigation(): bool
    // {
    //     if(auth()->user()->hasRole('teacher grade')){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function table(Table $table): Table
    {
        return $table
            ->query(Student::query()->myStudentGrade())
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('Cover')
                    ->button()
                    ->url(fn (Student $record): string => route('cover', $record)),
                    // ->openUrlInNewTab(),
                Action::make('Cover Indentity')
                    ->button()
                    ->url(fn (Student $record): string => route('cover.student', $record)),
                    // ->openUrlInNewTab(),
                Action::make('Report')
                    ->button()
                    ->url(fn (Student $record): string => route('report', $record)),
                    // ->openUrlInNewTab(),
                Action::make('Report Project')
                    ->button()
                    ->url(fn (Student $record): string => route('report.project', $record))
                    ->openUrlInNewTab()
                    ->visible($this->visible),
            ])
            ->bulkActions([
                // ...
            ])
            ->headerActions([
                Action::make('Leger Kelas')
                    ->button()
                    ->url(function(){
                        $myGrade = TeacherGrade::myGrade()->first();
                        return route('leger.grade', $myGrade->grade_id);
                    }),
            ]);
    }

}
