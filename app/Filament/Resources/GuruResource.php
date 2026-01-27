<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Filament\Resources\GuruResource\RelationManagers;
use App\Models\Guru;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportBulkAction as ActionsExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Actions\Tables\ImportAction;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Guru';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('lembaga_id')
                    ->relationship('lembaga', 'nama_lembaga')
                    ->required()
                    ->preload(),

                // Select::make('user_id')
                //     ->relationship('user', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->nullable()
                //     ->helperText('Opsional, jika guru memiliki akun login'),

                TextInput::make('nama')
                    ->required()
                    ->maxLength(255),

                TextInput::make('nik')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nik')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('lembaga.nama_lembaga')
                    ->label('Lembaga')
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Akun')
                    ->default('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('lembaga')
                    ->relationship('lembaga', 'nama_lembaga'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('jadikanUser')
                    ->label('Jadikan User')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->visible(fn($record) => is_null($record->user_id))
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        // 1️⃣ Buat base email dari nama
                        $baseName = Str::of($record->nama)
                            ->lower()
                            ->replace(' ', '')
                            ->ascii(); // amankan karakter aneh

                        $email = $baseName . '@gmail.com';
                        $counter = 1;

                        // 2️⃣ Jika email sudah ada → tambah angka
                        while (User::where('email', $email)->exists()) {
                            $email = $baseName . $counter . '@gmail.com';
                            $counter++;
                        }

                        // 3️⃣ Buat user
                        $user = User::create([
                            'name' => $record->nama,
                            'email' => $email,
                            'password' => Hash::make('12345678'),
                        ]);

                        // 4️⃣ Assign role guru
                        $user->assignRole('guru');

                        // 5️⃣ Hubungkan ke tabel guru
                        $record->update([
                            'user_id' => $user->id,
                        ]);

                        Notification::make()
                            ->title('Berhasil')
                            ->success()
                            ->body("User guru berhasil dibuat dengan email: {$email}")
                            ->send();
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make(),
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
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }
}
