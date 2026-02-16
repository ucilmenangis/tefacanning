<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->hidden(fn() => $this->record->hasRole('super_admin'))
                ->before(function () {
                    if ($this->record->hasRole('super_admin')) {
                        Notification::make()
                            ->title('Tidak dapat menghapus Super Admin')
                            ->danger()
                            ->send();
                        return false;
                    }

                    if ($this->record->id === auth()->id()) {
                        Notification::make()
                            ->title('Tidak dapat menghapus akun sendiri')
                            ->danger()
                            ->send();
                        return false;
                    }
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
