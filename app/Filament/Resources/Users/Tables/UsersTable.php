<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use App\User\DTO\ForgotPasswordDto;
use App\User\Services\Interfaces\AuthServiceInterface;
use App\User\Actions\AnonymizeUserAction;
use App\User\Models\User;
use Filament\Actions\Action;


class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('role')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label()),
                TextColumn::make('phone')->searchable()->toggleable(),
                TextColumn::make('created_at')->dateTime('d-m-Y')->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('send_reset')
                    ->label('Send Reset Link')
                    ->icon('heroicon-o-key')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalDescription('This will send a password reset link to the user\'s email.')
                    ->action(function (User $record) {
                        app(AuthServiceInterface::class)->forgotPassword(
                            new ForgotPasswordDto($record->email)
                        );
                        
                        Notification::make()
                            ->title('Reset link sent successfully')
                            ->success()
                            ->send();
                    }),
                Action::make('gdpr_delete')
                    ->label('Anonymize')
                    ->icon('heroicon-o-shield-exclamation')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('GDPR Data Erasure')
                    ->modalDescription('This will permanently anonymize all personal data. Financial records (orders) will be kept for legal compliance.')
                    ->action(fn (User $record) => app(AnonymizeUserAction::class)->execute($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
