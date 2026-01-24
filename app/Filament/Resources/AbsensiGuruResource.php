<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiGuruResource\Pages;
use App\Filament\Resources\AbsensiGuruResource\RelationManagers;
use App\Models\AbsensiGuru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AbsensiGuruResource extends Resource
{
    protected static ?string $model = AbsensiGuru::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Absensi Guru';
    protected static ?string $navigationGroup = 'Manajemen Absensi Guru';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('guru.nama')
                    ->label('Guru')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('lembaga.nama_lembaga')
                    ->label('Lembaga')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('jadwal.mataPelajaran.nama_mapel')
                    ->label('Jadwal')
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'hadir',
                        'warning' => 'izin',
                        'info'    => 'sakit',
                        'danger'  => 'alpha',
                    ])
                    ->sortable(),

                BadgeColumn::make('status_masuk')
                    ->label('Masuk')
                    ->colors([
                        'success' => 'tepat_waktu',
                        'danger'  => 'terlambat',
                    ])
                    ->placeholder('-'),

                IconColumn::make('radius_valid')
                    ->label('Radius')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                TextColumn::make('jarak_meter')
                    ->label('Jarak')
                    ->suffix(' m')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('metode')
                    ->badge()
                    ->colors([
                        'primary' => 'qr',
                        'gray'    => 'manual',
                    ]),

                TextColumn::make('waktu_scan')
                    ->label('Scan')
                    ->dateTime('H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListAbsensiGurus::route('/'),
            'create' => Pages\CreateAbsensiGuru::route('/create'),
            'edit' => Pages\EditAbsensiGuru::route('/{record}/edit'),
        ];
    }
}
