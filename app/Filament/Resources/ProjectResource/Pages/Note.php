<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\ProjectNote;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextInputColumn;

class Note extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = ProjectResource::class;

    protected static string $view = 'filament.resources.project-resource.pages.note';

    public $projectId = -1;

    public function mount($record)
    {
        $this->projectId = $record;
    }

    public function table(Table $table): Table
    {
        return $table
                ->query(ProjectNote::query())
                ->columns([
                    TextColumn::make('student.name'),
                    TextInputColumn::make('note'),
                ])
                ->bulkActions([
                    BulkAction::make('scoring')
                        ->form([
                            TextInput::make('note')
                        ])
                        ->action(function (Collection $records, $data) {
                            $records->each->update($data);
                        })
                ])
                ->modifyQueryUsing(function (Builder $query){
                    $query->where('project_id', $this->projectId);
                })
                ->paginated(false);
    }
}
