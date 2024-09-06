<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingTransactionResource\Pages;
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

    // 'name', ---
    // 'trx_id', ---
    // 'proof', ---
    // 'phone_number', ---
    // 'is_paid', ===
    // 'total_amount', ---
    // 'car_store_id',
    // 'car_service_id',
    // 'started_at', ---
    // 'time_at' ----

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
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}
