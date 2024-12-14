<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make([
                Group::make([
                    Forms\Components\TextInput::make('name')
                        ->placeholder('Enter name')
                        ->required()
                        ->reactive(),

                    Forms\Components\TextInput::make('email')
                        ->placeholder('Enter Email Address')
                        ->required()
                        ->email()
                        ->unique(User::class, 'email', fn ($record) => $record),

                    Forms\Components\TextInput::make('password')
                        ->label(fn (string $context): string => $context === 'create'
                                                    ? 'Password'
                                                    : ($context === 'edit' ? 'Reset Password' : 'Your Password'))
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create')
                        ->placeholder(fn (string $context): string => $context === 'create'
                                                    ? 'Enter new password'
                                                    : ($context === 'edit' ? 'Enter new password to reset' : "Don't share your password")),

                ])->columns(2),
            ])->columnSpan([
                12,
                'lg' => 9,
            ]),

            Group::make([
                Section::make([
                    Placeholder::make('created_at')
                        ->label('Created at:')
                        ->content(fn ($record): string => $record ? $record->created_at->diffForHumans() : '-----------'),

                    Placeholder::make('updated_at')
                        ->label('Updated at:')
                        ->content(fn ($record): string => $record ? $record->updated_at->diffForHumans() : '-----------'),

                ])->columns(2),
            ])
                ->columnSpan([
                    12,
                    'lg' => 3,
                ]),

        ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
