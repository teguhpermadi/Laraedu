<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use App\Imports\ExamImport;
use App\Models\Exam;
use App\Models\TeacherSubject;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ListExams extends ListRecords
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Action::make('Exam Evaluation')
                ->url(route('filament.admin.resources.exams.evaluation')),
            Action::make('download')
                ->form([
                    Select::make('teacher_subject_id')
                        ->options(
                            TeacherSubject::mySubject()->with('grade')->get()->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'code' => $item->subject->code . ' - ' . $item->grade->name,
                                ];
                            })->pluck('code', 'id')
                        )
                        ->required()
                ])
                ->action(function($data){
                    // dd($data['teacher_subject_id']);
                    return to_route('export.exam', ['teacher_subject_id' => $data['teacher_subject_id']]);                    
                }),
            Action::make('upload')
                ->form([
                    FileUpload::make('file')
                        ->directory('uploads')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-excel'])
                        ->getUploadedFileNameForStorageUsing(
                            function (TemporaryUploadedFile $file){
                                return 'exam.'. $file->getClientOriginalExtension();
                            }
                        )
                        ->required()
                ])
                ->action(function(array $data){
                    $exams = Excel::toArray(new ExamImport, storage_path('/app/public/'.$data['file']));
                    // dd($exams);
                    // $data = [];
                    foreach ($exams as $rows) {
                        foreach ($rows as $value) {
                            Exam::where([
                                'teacher_subject_id' => $value['teacher_subject_id'],
                                'student_id' => $value['student_id'],
                            ])
                            ->update([
                                'score_middle' => $value['score_middle'],
                                'score_last' => $value['score_last'],
                            ]);
                        }
                    }
                })
        ];
    }

    public function getTabs(): array
    {
        $subjects = TeacherSubject::mySubject();
        $tabs = [];
        if($subjects->count() != 0){
            foreach ($subjects->get() as $subject) {
                $tabs[$subject->id] = Tab::make(Str::upper($subject->subject->code.'-'.$subject->grade->name))
                    ->modifyQueryUsing(function(Builder $query) use ($subject){
                        $studentId = $subject->grade->studentGrade->pluck('student_id');
                        $query->whereIn('student_id', $studentId)
                            ->where('teacher_subject_id', $subject->id)
                            ->orderBy('student_id');
                    });
            }
        } else {
            $tabs = [
                '-' => Tab::make()
                    ->icon('heroicon-m-x-mark')
                    ->modifyQueryUsing(function(Builder $query){
                        $query->where('student_id', -1);
                    })
            ];
        }
        return $tabs;
    }
}
