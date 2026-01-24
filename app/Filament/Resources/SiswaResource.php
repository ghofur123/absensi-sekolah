<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Siswa')
                    ->schema([
                        Select::make('lembaga_id')
                            ->label('Lembaga')
                            ->relationship('lembaga', 'nama_lembaga')
                            ->required()
                            ->reactive(), // penting agar select lain bisa merespon perubahan

                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->required()
                            ->options(function ($get) {
                                $lembagaId = $get('lembaga_id');
                                if (!$lembagaId) {
                                    return []; // kosong kalau lembaga belum dipilih
                                }
                                return \App\Models\Kelas::where('lembaga_id', $lembagaId)
                                    ->pluck('nama_kelas', 'id');
                            })
                            ->disabled(fn($get) => !$get('lembaga_id')), // nonaktif sampai lembaga dipilih

                        TextInput::make('nisn')
                            ->label('NISN')
                            ->required()
                            ->maxLength(20),

                        TextInput::make('nama_siswa')
                            ->label('Nama Siswa')
                            ->required(),

                        Select::make('jenis_kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),

                        Textarea::make('alamat')
                            ->columnSpanFull(),

                        Select::make('status')
                            ->options([
                                'aktif' => 'Aktif',
                                'lulus' => 'Lulus',
                                'pindah' => 'Pindah',
                            ])
                            ->default('aktif'),

                        TextInput::make('no_wa')
                            ->label('No WhatsApp')
                            ->tel()
                            ->helperText('Contoh: 628xxxxxxxxx'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_siswa')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->sortable(),

                TextColumn::make('lembaga.nama_lembaga')
                    ->label('Lembaga')
                    ->sortable(),

                TextColumn::make('jenis_kelamin'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'aktif',
                        'warning' => 'pindah',
                        'danger' => 'lulus',
                    ]),

                TextColumn::make('no_wa')
                    ->label('WA'),
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
