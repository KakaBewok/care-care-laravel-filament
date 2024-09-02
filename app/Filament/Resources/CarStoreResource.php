<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarStoreResource\Pages;
use App\Filament\Resources\CarStoreResource\RelationManagers;
use App\Models\CarStore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarStoreResource extends Resource
{
    protected static ?string $model = CarStore::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->helperText('Store name e.g. Rizal Store')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\FileUpload::make('thumbnail')
                    ->directory('photos')
                    ->image()
                    ->maxSize(4024)
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->required(),
                Forms\Components\Toggle::make('is_open')
                    ->label('Is Open')
                    ->required(),
                Forms\Components\Toggle::make('is_full')
                    ->label('Is Full')
                    ->required(),
                Forms\Components\Select::make('city_id')
                    ->relationship('city', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                    ]),
                Forms\Components\TextArea::make('address')
                    ->rows(10)
                    ->cols(20)
                    ->required(),
                Forms\Components\TextInput::make('phone_number')
                    ->label('Phone Number')
                    ->numeric()
                    ->tel()
                    ->required(),
                Forms\Components\TextInput::make('cs_name')
                    ->label('Customer Name')
                    ->maxLength(255)
                    ->required(),
            ]);
    }

    //     'name',-
    //     'slug',-
    //     'thumbnail', -
    //     'is_open', -
    //     'is_full', -
    //     'city_id', - 
    //     'address', -
    //     'phone_number', -
    //     'cs_name', -

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListCarStores::route('/'),
            'create' => Pages\CreateCarStore::route('/create'),
            'edit' => Pages\EditCarStore::route('/{record}/edit'),
        ];
    }
}
