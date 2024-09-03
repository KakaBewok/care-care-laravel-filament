<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarStoreResource\Pages;
use App\Filament\Resources\CarStoreResource\RelationManagers;
use App\Models\CarStore;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                TextInput::make('name')
                    ->helperText('Store name e.g. Rizal Store')
                    ->maxLength(255)
                    ->required(),
                FileUpload::make('thumbnail')
                    ->directory('photos')
                    ->image()
                    ->maxSize(4024)
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->required(),
                Toggle::make('is_open')
                    ->label('Is Open')
                    ->required(),
                Toggle::make('is_full')
                    ->label('Is Full Book')
                    ->required(),

            Repeater::make('carServices') // Menggunakan 'storeServices' sebagai nama field untuk StoreService relationship
            ->relationship() // Menghubungkan ke relasi StoreService pada model CarStore
            ->schema([
                Select::make('car_service_id')
                ->relationship('service', 'name') // Menghubungkan ke relasi CarService pada model StoreService
                ->required(),
            ])
            ->collapsible(),

                Select::make('city_id')
                    ->relationship('city', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                            TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                    ]),
                Forms\Components\TextArea::make('address')
                    ->rows(10)
                    ->cols(20)
                    ->required(),
                TextInput::make('phone_number')
                    ->label('Phone Number')
                    ->numeric()
                    ->tel()
                    ->required(),
                TextInput::make('cs_name')
                    ->label('Customer Name')
                    ->maxLength(255)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                IconColumn::make('is_open')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('warning'),
                IconColumn::make('is_full')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('warning'),
                TextColumn::make('phone_number'),
                TextColumn::make('cs_name')->label('Customer Name'),
                ImageColumn::make('thumbnail')->square(),
                TextColumn::make('city.name')->label('City Name'),
            ])
            ->filters([
                Filter::make('is_open')
                    ->label('Store Open')
                    ->toggle()
                    ->query(fn (Builder $query) : Builder => $query->where('is_open', true)),
                Filter::make('is_full')
                    ->label('Full Book')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->where('is_full', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
