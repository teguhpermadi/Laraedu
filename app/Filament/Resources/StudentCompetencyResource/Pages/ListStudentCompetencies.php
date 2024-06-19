<?php

namespace App\Filament\Resources\StudentCompetencyResource\Pages;

use App\Filament\Resources\StudentCompetencyResource;
use App\Imports\StudentCompetencyImport;
use App\Models\StudentCompetency;
use App\Models\TeacherSubject;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ListStudentCompetencies extends ListRecords
{
    protected static string $resource = StudentCompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            Action::make('assesment')
                ->url(route('filament.admin.resources.student-competencies.assesment')),
            // Action::make('evaluation')
            //     ->url(route('filament.admin.resources.student-competencies.evaluation')),
            // Action::make('leger')
            //     ->form([
            //         Select::make('teacher_subject_id')
            //             ->options(
            //                 TeacherSubject::mySubject()->with('grade')->get()->map(function ($item) {
            //                     return [
            //                         'id' => $item->id,
            //                         'code' => $item->subject->code . ' - ' . $item->grade->name,
            //                     ];
            //                 })->pluck('code', 'id')
            //             )
            //             ->required()
            //     ])
            //     ->action(function($data){
            //         return to_route('leger', ['id' => $data['teacher_subject_id']]);                    
            //     }),
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
                    return to_route('export.studentCompetencySheet', ['teacher_subject_id' => $data['teacher_subject_id']]);                    
                }),
            Action::make('upload')
                ->form([
                    FileUpload::make('file')
                        ->directory('uploads')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-excel'])
                        ->getUploadedFileNameForStorageUsing(
                            function (TemporaryUploadedFile $file){
                                return 'siswa.'. $file->getClientOriginalExtension();
                            }
                        )
                        ->required()
                ])
                ->action(function(array $data){
                    $studentCompetencies = Excel::toArray(new StudentCompetencyImport, storage_path('/app/public/'.$data['file']));
                    
                    $data = [];
                    foreach ($studentCompetencies as $row) {
                        foreach ($row as $value) {
                            StudentCompetency::where([
                                'teacher_subject_id' => $value['teacher_subject_id'],
                                'student_id' => $value['student_id'],
                                'competency_id' => $value['competency_id'],
                            ])
                            ->update([
                                'score' => $value['score'],
                                'score_skill' => $value['score_skill'],
                            ]);
                        }
                    }
                })
        ];
    }

    public function getTabs(): array
    {
        $subjects = TeacherSubject::with('competencies', 'grade')->mySubject();
        $tabs = [];
        if($subjects->count() != 0){
            foreach ($subjects->get() as $subject) {
                $tabs[$subject->id] = Tab::make(Str::upper($subject->subject->code.'-'.$subject->grade->name))
                    ->modifyQueryUsing(function(Builder $query) use ($subject){
                        $competencyId = $subject->competencies->pluck('id');
                        $studentId = $subject->grade->studentGrade->pluck('student_id');
                        $query->whereIn('competency_id',$competencyId)
                            ->whereIn('student_id', $studentId)
                            ->orderBy('student_id');
                    });
            }
        } else {
            $tabs = [
                '-' => Tab::make()
                    ->icon('heroicon-m-x-mark')
                    ->modifyQueryUsing(function(Builder $query){
                        $query->where('student_id', 0);
                    })
            ];
        }
        return $tabs;
    }
}
