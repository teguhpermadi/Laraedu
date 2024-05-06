<?php

namespace App\Filament\Resources\CompetencyResource\Pages;

use App\Filament\Resources\CompetencyResource;
use App\Imports\CompetencyImport;
use App\Models\Competency;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ListCompetencies extends ListRecords
{
    protected static string $resource = CompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
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
                    return to_route('export.competency', ['teacher_subject_id' => $data['teacher_subject_id']]);                    
                }),
            Action::make('upload')
                ->form([
                    FileUpload::make('file')
                    ->directory('uploads')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-excel'])
                        ->preserveFilenames()
                        // ->getUploadedFileNameForStorageUsing(
                        //     function (TemporaryUploadedFile $file){
                        //         return 'kompetensi.'. $file->getClientOriginalExtension();
                        //     }
                        // )
                    ->required()
                ])
                ->action(function(array $data){
                    Excel::import(new CompetencyImport, storage_path('/app/public/'.$data['file']));
                }),
        ];
    }

    public function getTabs(): array
    {
        $subjects = TeacherSubject::with('competencies', 'grade')->mySubject();
        $tabs = [];
        if($subjects->count() != 0){
            foreach ($subjects->get() as $subject) {
                $tabs[$subject->id] = Tab::make($subject->subject->code.'-'.$subject->grade->name)
                    ->modifyQueryUsing(function(Builder $query) use ($subject){
                        $competencyId = $subject->competencies->pluck('id');
                        $query->whereIn('id',$competencyId);
                    })
                    ->badge(function() use ($subject){
                        $competencyId = $subject->competencies->pluck('id');
                        return Competency::whereIn('id',$competencyId)->count();
                    })
                    ->badgeColor('success');
            }
        } else {
            $tabs = [
                '-' => Tab::make()
                    ->icon('heroicon-m-x-mark')
                    // ->modifyQueryUsing(function(Builder $query){
                    //     $query->where('student_id', 0);
                    // })
            ];
        }
        return $tabs;
    }
}
