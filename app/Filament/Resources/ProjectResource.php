<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\Pages\Assesment;
use App\Filament\Resources\ProjectResource\Pages\ProjectAssesment;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Filament\Resources\ProjectResource\RelationManagers\ProjectTargetRelationManager;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Project;
use App\Models\ProjectCoordinator;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('academic_year_id')->default(AcademicYear::active()->first()->id),
                Hidden::make('teacher_id')->default(auth()->user()->userable->userable_id),
                Select::make('grade_id')
                    ->options(function(){
                        $data = ProjectCoordinator::with('grade')->where('teacher_id', auth()->user()->userable->userable_id)->get()->map(function ($grade) {
                            return [
                                'id' => $grade->grade->id,
                                'name' => $grade->grade->name,
                            ];
                        })->pluck('name', 'id');
                        
                        return $data;                        
                    })
                    ->required()
                    ->reactive(),
                Select::make('phase')
                    ->options(function(Get $get){
                        if( $get('grade_id')){
                            $gradeId = $get('grade_id');
                            $fase = Grade::find($gradeId)->fase;
                            return [
                                $fase => 'Fase '.$fase
                            ];
                        }

                        return [];
                    })
                    ->required(),
                TextInput::make('name')->label('Project Title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('grade.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Assesment')
                    ->button()
                    ->url(function(Project $record){
                        return route('filament.admin.resources.projects.assesment', ['record'=>$record]);
                    }),
                Action::make('Leger')
                    ->button()
                    ->openUrlInNewTab()
                    ->url(function(Project $record){
                        return route('leger.project', ['id'=>$record]);
                    }),
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
            ProjectTargetRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'assesment' => Assesment::route('/{record}/assesment'),
        ];
    }    
}
