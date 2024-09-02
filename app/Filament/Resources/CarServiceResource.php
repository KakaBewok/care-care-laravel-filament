<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarServiceResource\Pages;
use App\Filament\Resources\CarServiceResource\RelationManagers;
use App\Models\CarService;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class CarServiceResource extends Resource
{
    protected static ?string $model = CarService::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->helperText('Service name e.g. Car Wash')
                    ->maxLength(255)
                    ->required(),       
                Forms\Components\TextInput::make('price')->required()
                    ->numeric()
                    ->prefix('IDR')
                    ->required(),
                Forms\Components\TextInput::make('duration_in_hour')->required()
                    ->numeric()
                    ->maxLength(255)
                    ->required(),
                Forms\Components\FileUpload::make('photo')
                    ->directory('photos')
                    ->image()
                    ->maxSize(4024) 
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->required(),
                Forms\Components\FileUpload::make('icon')
                    ->directory('icons')
                    ->image()
                    ->maxSize(1024)
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->required(),
                Forms\Components\TextArea::make('about')
                    ->rows(10)
                    ->cols(20)
                    ->required(),
            ]);
    }
    // 'name', 'price', 'about', 'photo', 'duration_in_hour', 'slug'

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('duration_in_hour'),
                Tables\Columns\ImageColumn::make('icon'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCarServices::route('/'),
            'create' => Pages\CreateCarService::route('/create'),
            'edit' => Pages\EditCarService::route('/{record}/edit'),
        ];
    }
}
