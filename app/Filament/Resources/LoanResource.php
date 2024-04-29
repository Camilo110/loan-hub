<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\RelationManagers;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Client')
                        ->schema([
                            Forms\Components\Select::make('client_id')
                                ->relationship('client', 'name')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('code')
                                        ->numeric()
                                        ->required(),
                                    Forms\Components\TextInput::make('name')
                                        ->required(),
                                    Forms\Components\TextInput::make('email')
                                        ->email()
                                        ->required(),
                                    Forms\Components\Select::make('programs')
                                        ->relationship('programs', 'name')
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                ])
                                ->required(),
                            Forms\Components\Select::make('program_id')
                                ->relationship('program', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),
                        ]),
                    Forms\Components\Wizard\Step::make('Location')
                        ->schema([
                            Forms\Components\Select::make('classroom_id')
                                ->relationship('classroom', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),
                        ]),
                    Forms\Components\Wizard\Step::make('Devices')
                        ->schema([
                            Forms\Components\Repeater::make('devices')
                                ->schema([
                                    Forms\Components\Select::make('device_id')
                                        ->relationship('device', 'name')
                                        ->required()
                                        ->searchable()
                                        ->preload(),
                                ])
                                ->required()
                                ->addActionLabel('Add device')
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('observations')
                                ->nullable()
                                ->maxLength(255),
                        ]),
                    Forms\Components\Wizard\Step::make('Confirmation')
                        ->schema([])
                ])
                    ->columnSpanFull(),


                // Forms\Components\Select::make('status')
                //     ->options([
                //         'Prestado' => 'Prestado',
                //         'Devuelto' => 'Devuelto',
                //     ])
                //     ->required(),
                // Forms\Components\DatePicker::make('loan_date')
                //     ->required(),
                // Forms\Components\DatePicker::make('return_date'),

                // Forms\Components\TextInput::make('activity')
                //     ->nullable(),


                // Forms\Components\Select::make('User_id')
                //     ->relationship('user', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->required(),

                // Forms\Components\Select::make('User_id_return')
                //     ->relationship('user', 'name')
                //     ->searchable()
                //     ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program.name')
                    ->label('Program'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('loan_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('return_date')
                    ->dateTime()
                    ->sortable()
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
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }
}
