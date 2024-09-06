<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\Pages\CreateBookingTransaction;
use App\Filament\Resources\BookingTransactionResource\Pages\EditBookingTransaction;
use App\Filament\Resources\BookingTransactionResource\Pages\ListBookingTransactions;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use App\Models\BookingTransaction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Customer Name')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('trx_id')
                    ->label('Transaction Id')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('phone_number')
                    ->label('Phone Number')
                    ->numeric()
                    ->tel()
                    ->required(),
                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->numeric()
                    ->prefix('IDR')
                    ->required(),
                DatePicker::make('started_at')->required(),
                TimePicker::make('time_at')->withoutSeconds()->required(),
                Toggle::make('is_paid')
                    ->label('Is Paid')
                    ->required(),
                FileUpload::make('proof')
                    ->label('Transaction Proof')
                    ->directory('photos')
                    ->image()
                    ->maxSize(4024)
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->required(),
                Select::make('car_store_id')
                    ->relationship('store_details', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('car_service_id')
                    ->relationship('service_details', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('trx_id')->searchable(),
                IconColumn::make('is_paid')
                    ->boolean()
                    ->trueColor('info')
                    ->falseColor('warning'),
                TextColumn::make('total_amount'),
                TextColumn::make('service_details.name')->label('Service'),
                TextColumn::make('store_details.name')->label('Store'),
                ImageColumn::make('proof')->square(),
                TextColumn::make('started_at'),
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
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}
