<?php

namespace App\Filament\Resources\ApiTokenResource\Pages;

use App\Filament\Resources\ApiTokenResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateApiToken extends CreateRecord
{
    protected static string $resource = ApiTokenResource::class;

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('API токен создан')
            ->body("Ваш токен: {$this->record->token}")
            ->warning()
            ->persistent()
            ->send();
    }
}
