<?php

namespace App\Filament\Customer\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),

                TextInput::make('phone')
                    ->label('No. Telepon')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->placeholder('08xxxxxxxxxx')
                    ->prefixIcon('heroicon-o-phone'),

                TextInput::make('organization')
                    ->label('Organisasi / Instansi')
                    ->maxLength(255)
                    ->placeholder('Nama organisasi atau instansi Anda')
                    ->prefixIcon('heroicon-o-building-office'),

                Textarea::make('address')
                    ->label('Alamat')
                    ->required()
                    ->rows(3)
                    ->maxLength(500)
                    ->placeholder('Alamat lengkap Anda'),

                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
