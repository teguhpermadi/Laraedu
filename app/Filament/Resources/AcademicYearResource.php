<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicYearResource\Pages;
use App\Filament\Resources\AcademicYearResource\RelationManagers;
use App\Jobs\CopyDataAcademicYear;
use App\Models\AcademicYear;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AcademicYearResource extends Resource
{
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Database';

    protected static ?string $model = AcademicYear::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('year')->required(),
                Select::make('semester')->options([
                    'ganjil' => 'Ganjil', 
                    'genap' => 'Genap',
                ])->required(),
                Select::make('active')->default(0)->boolean()->required(),
                Select::make('teacher_id')
                    ->label('Head Master')
                    ->required()
                    ->options(Teacher::pluck('name', 'id')),
                DatePicker::make('date_report_half')
                    ->label('date of report half semester'),
                DatePicker::make('date_report')
                    ->required()
                    ->label('date of report'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year'),
                TextColumn::make('semester'),
                TextColumn::make('teacher.name'),
                TextColumn::make('date_report_half'),
                TextColumn::make('date_report'),
                IconColumn::make('active')->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
                Action::make('Active')
                    ->action(function(AcademicYear $record){
                        AcademicYear::setActive($record->id);
                    })
                    // ->visible(auth()->user()->hasPermissionTo('activated_academic::year'))
                    ->button(),
                Action::make('Copy Data')
                    ->requiresConfirmation()
                    ->action(
                        function(AcademicYear $record){
                            // copy data from another academic year
                            dispatch(new CopyDataAcademicYear($record->id));
                        }
                    )
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListAcademicYears::route('/'),
            'create' => Pages\CreateAcademicYear::route('/create'),
            'view' => Pages\ViewAcademicYear::route('/{record}'),
            'edit' => Pages\EditAcademicYear::route('/{record}/edit'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
