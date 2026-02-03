<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiResource\Pages;
use App\Filament\Resources\AbsensiResource\RelationManagers;
use App\Models\Absensi;
use Carbon\Carbon;
use CCK\FilamentQrcodeScannerHtml5\BarcodeScannerAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Absensi Siswa';
    protected static ?string $pluralModelLabel = 'Absensi Siswa';
    protected static ?string $modelLabel = 'Absensi Siswa';
    protected static ?string $navigationGroup = 'Absensi & Kehadiran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('barcode')
                    ->suffixAction(BarcodeScannerAction::make())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama_siswa')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('siswa.nisn')
                    ->label('NISN')
                    ->searchable(),

                // TextColumn::make('jadwal.hari')
                //     ->label('Jadwal')
                //     ->toggleable(),
                TextColumn::make('jadwal.mataPelajaran.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->toggleable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'hadir',
                        'warning' => 'izin',
                        'info'    => 'sakit',
                        'danger'  => 'alpa',
                    ])
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                BadgeColumn::make('status_masuk')
                    ->label('Status Masuk')
                    ->getStateUsing(function ($record) {

                        // 🔴 hanya hadir
                        if ($record->status !== 'hadir') {
                            return null;
                        }

                        // belum scan
                        if (! $record->waktu_scan) {
                            return null;
                        }

                        // jadwal tidak valid
                        if (! $record->jadwal || ! $record->jadwal->jam_mulai || ! $record->jadwal->jam_selesai) {
                            return null;
                        }

                        Carbon::setLocale('id');

                        // 1. Waktu scan
                        $scan = Carbon::parse($record->waktu_scan);

                        // 2. Hari scan (rabu, kamis, dll)
                        $hariScan = strtolower($scan->translatedFormat('l'));

                        // 3. Hari jadwal (SUDAH ARRAY)
                        $hariJadwal = array_map(
                            fn($hari) => strtolower(trim($hari)),
                            $record->jadwal->hari
                        );

                        if (! in_array($hariScan, $hariJadwal)) {
                            return 'Di Luar Jadwal';
                        }

                        // 4. Jam jadwal ikut tanggal scan
                        $mulai = $scan->copy()
                            ->setTimeFromTimeString($record->jadwal->jam_mulai);

                        $selesai = $scan->copy()
                            ->setTimeFromTimeString($record->jadwal->jam_selesai);

                        // 5. Antisipasi jadwal lintas malam
                        if ($selesai->lt($mulai)) {
                            $selesai->addDay();
                        }

                        // 6. Toleransi
                        $batasAwal = $mulai->copy()->subMinutes($record->jadwal->batas_awal);
                        $batasPas  = $mulai->copy()->addMinutes($record->jadwal->batas_pas);

                        // 7. Logika final
                        if ($scan->lt($batasAwal)) {
                            return 'Di Luar Jadwal';
                        }

                        if ($scan->gte($batasAwal) && $scan->lt($mulai)) {
                            return 'Terlalu Awal';
                        }

                        if ($scan->gte($mulai) && $scan->lte($batasPas)) {
                            return 'Tepat Waktu';
                        }

                        if ($scan->gt($batasPas) && $scan->lte($selesai)) {
                            return 'Terlambat';
                        }

                        return 'Di Luar Jadwal';
                    })
                    ->colors([
                        'success' => 'Tepat Waktu',
                        'warning' => 'Terlambat',
                        'info'    => 'Belum Waktu',
                        'danger'  => 'Di Luar Jadwal',
                    ])
                    ->placeholder('-'),


                TextColumn::make('waktu_scan')
                    ->label('Waktu Scan')
                    // ->dateTime('d M Y H:i')
                    ->formatStateUsing(
                        fn($state) =>
                        \Carbon\Carbon::parse($state)->format('d M Y H:i')
                    )
                    ->sortable(),

                TextColumn::make('users.name')
                    ->label('Diabsenkan Oleh')
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('tanggal_awal')
                            ->label('Tanggal Awal'),
                        DatePicker::make('tanggal_akhir')
                            ->label('Tanggal Akhir'),
                    ])
                    ->query(function (Builder $query, array $data) {

                        return $query
                            ->when(
                                $data['tanggal_awal'] ?? null,
                                fn(Builder $query, $date) =>
                                $query->whereDate('waktu_scan', '>=', $date)
                            )
                            ->when(
                                $data['tanggal_akhir'] ?? null,
                                fn(Builder $query, $date) =>
                                $query->whereDate('waktu_scan', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
