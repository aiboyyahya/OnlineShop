<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone_number')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(255),
                Textarea::make('address')
                    ->label('Alamat')
                    ->maxLength(65535),
                TextInput::make('role')
                    ->maxLength(255),
                FileUpload::make('profile_photo')
                    ->label('Foto Profil')
                    ->image(),
                Textarea::make('checkouts_products')
                    ->label('Products Checked Out')
                    ->disabled(),
                Textarea::make('reviews_products')
                    ->label('Products Reviewed')
                    ->disabled(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
            ]);
    }
}
