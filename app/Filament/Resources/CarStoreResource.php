<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarStoreResource\Pages;
use App\Filament\Resources\CarStoreResource\RelationManagers\PhotosRelationManager;
use App\Models\CarService;
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
use Filament\Tables\Filters\SelectFilter;
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

                Repeater::make('storeServices')
                    ->relationship()
                    ->schema([
                        Select::make('car_service_id')
                            ->relationship('service', 'name')
                            ->required(),
                    ]),

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
                    ->label('Customer Service Name')
                    ->maxLength(255)
                    ->required(),

                Repeater::make('photos')
                    ->relationship()
                    ->schema([
                        FileUpload::make('photo')
                            ->directory('photos')
                            ->image()
                            ->maxSize(4024)
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ]),
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
                TextColumn::make('cs_name')->label('Customer Service Name'),
                ImageColumn::make('thumbnail')->square(),
                TextColumn::make('city.name')->label('City'),
            ])
            ->filters([
                Filter::make('is_open')
                    ->label('Store Open')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->where('is_open', true)),
                Filter::make('is_full')
                    ->label('Full Book')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->where('is_full', true)),
                SelectFilter::make('city_id')
                    ->label('City')
                    ->relationship('city', 'name'),
                SelectFilter::make('car_service_id')
                    ->label('Service')
                    ->options(CarService::pluck('name', 'id'))
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->whereHas('storeServices', function ($query) use ($data) {
                                $query->where('car_service_id', $data['value']);
                            });
                        }
                    })

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
            // PhotosRelationManager::class, //comment sementara
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
