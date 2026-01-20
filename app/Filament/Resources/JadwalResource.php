<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalResource\Pages;
use App\Filament\Resources\JadwalResource\RelationManagers;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Siswa;
use Carbon\Carbon;
use CCK\FilamentQrcodeScannerHtml5\BarcodeScannerAction;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class JadwalResource extends Resource
{
    protected static ?string $model = Jadwal::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Jadwal';
    protected static ?string $pluralModelLabel = 'Jadwal';
    protected static ?string $navigationGroup = 'Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('lembaga_id')
                    ->relationship('lembaga', 'nama_lembaga')
                    ->required()
                    ->preload(),

                Select::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->required()
                    ->preload(),

                Select::make('guru_id')
                    ->relationship('guru', 'nama')
                    ->required()
                    ->preload(),

                Select::make('mata_pelajaran_id')
                    ->relationship('mataPelajaran', 'nama_mapel')
                    ->required()
                    ->preload(),

                Select::make('hari')
                    ->options([
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                        'sabtu' => 'Sabtu',
                    ])
                    ->required(),

                TimePicker::make('jam_mulai')
                    ->required(),

                TimePicker::make('jam_selesai')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hari')
                    ->badge()
                    ->sortable(),

                TextColumn::make('jam_mulai')
                    ->time('H:i'),

                TextColumn::make('jam_selesai')
                    ->time('H:i'),

                TextColumn::make('mataPelajaran.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->searchable(),

                TextColumn::make('guru.nama')
                    ->label('Guru')
                    ->searchable(),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas'),

                TextColumn::make('lembaga.nama_lembaga')
                    ->label('Lembaga'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('hari')
                    ->options([
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                        'sabtu' => 'Sabtu',
                    ]),

                Tables\Filters\SelectFilter::make('lembaga')
                    ->relationship('lembaga', 'nama_lembaga'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('absensi')
                    ->label('Absensi Manual')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('success')
                    ->modalHeading('Absensi Siswa')
                    ->modalWidth('7xl')
                    ->fillForm(function ($record) {
                        return [
                            'lembaga_id' => null,
                            'kelas_id' => null,
                            'jadwal_id' => $record->id,
                            'daftar_absensi' => [],
                        ];
                    })
                    ->form([
                        Select::make('lembaga_id')
                            ->label('Lembaga')
                            ->options(Lembaga::pluck('nama_lembaga', 'id'))
                            ->required()
                            ->dehydrated()
                            ->live()
                            ->afterStateUpdated(function (\Filament\Forms\Set $set) {
                                $set('kelas_id', null);
                                $set('daftar_absensi', []);
                            }),

                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(function (callable $get) {
                                $lembagaId = $get('lembaga_id');

                                if (!$lembagaId) {
                                    return [];
                                }

                                return Kelas::where('lembaga_id', $lembagaId)
                                    ->pluck('nama_kelas', 'id');
                            })
                            ->required()
                            ->dehydrated()
                            ->live()
                            ->disabled(fn(callable $get) => !$get('lembaga_id'))
                            ->placeholder('Pilih lembaga terlebih dahulu')
                            ->afterStateUpdated(function (\Filament\Forms\Get $get, \Filament\Forms\Set $set, $state) {
                                if (!$state) {
                                    $set('daftar_absensi', []);
                                    return;
                                }

                                $jadwalId = $get('jadwal_id');

                                // Ambil data absensi hari ini untuk jadwal ini
                                $absensiHariIni = Absensi::where('jadwal_id', $jadwalId)
                                    ->whereDate('created_at', today())
                                    ->pluck('status', 'siswa_id')
                                    ->toArray();

                                // Ambil siswa sesuai kelas yang dipilih
                                $siswaList = Siswa::where('kelas_id', $state)
                                    ->get()
                                    ->map(function ($siswa, $index) use ($absensiHariIni) {
                                        return [
                                            'siswa_id' => $siswa->id,
                                            'nama_siswa' => $siswa->nama_siswa,
                                            // Cek di database, jika ada gunakan status dari DB, jika tidak default 'alpa'
                                            'status' => $absensiHariIni[$siswa->id] ?? 'alpa',
                                            'nomor' => $index + 1,
                                        ];
                                    })
                                    ->toArray();

                                $set('daftar_absensi', $siswaList);
                            }),

                        Hidden::make('jadwal_id'),

                        Repeater::make('daftar_absensi')
                            ->label('Daftar Siswa')
                            ->schema([
                                Hidden::make('siswa_id'),
                                TextInput::make('nama_siswa')
                                    ->label('Nama Siswa')
                                    ->disabled()
                                    ->prefix(fn($get) => $get('nomor') . '.')
                                    ->columnSpan(1),
                                ToggleButtons::make('status')
                                    ->label(false)
                                    ->options([
                                        'hadir' => 'Hadir',
                                        'izin'  => 'Izin',
                                        'sakit' => 'Sakit',
                                        'alpa'  => 'Alpa',
                                    ])
                                    ->icons([
                                        'hadir' => 'heroicon-o-check-circle',
                                        'izin' => 'heroicon-o-document-text',
                                        'sakit' => 'heroicon-o-face-frown',
                                        'alpa' => 'heroicon-o-x-circle',
                                    ])
                                    ->colors([
                                        'hadir' => 'success',
                                        'izin' => 'info',
                                        'sakit' => 'warning',
                                        'alpa' => 'danger',
                                    ])
                                    ->inline()
                                    ->grouped()
                                    ->required()
                                    ->columnSpan(2),
                                Hidden::make('nomor'),
                            ])
                            ->columns(3)
                            ->deletable(false)
                            ->addable(false)
                            ->reorderable(false)
                            ->defaultItems(0)
                            ->visible(fn(callable $get) => !empty($get('daftar_absensi'))),
                    ])
                    ->action(function ($record, $data) {
                        $userId = Auth::id();
                        $now = now();

                        foreach ($data['daftar_absensi'] ?? [] as $item) {

                            // Ambil absensi hari ini per siswa
                            $absensiHariIni = Absensi::where('jadwal_id', $record->id)
                                ->where('siswa_id', $item['siswa_id'])
                                ->whereDate('created_at', today())
                                ->first();

                            // Jika belum ada absensi → langsung buat
                            if (! $absensiHariIni) {
                                Absensi::create([
                                    'siswa_id' => $item['siswa_id'],
                                    'jadwal_id' => $record->id,
                                    'diabsenkan_oleh_user_id' => $userId,
                                    'status' => $item['status'],
                                    'waktu_scan' => $now,
                                ]);
                                continue;
                            }

                            // Jika status sama → tidak perlu apa-apa
                            if ($absensiHariIni->status === $item['status']) {
                                continue;
                            }

                            // Jika status berbeda → hapus & buat ulang
                            $absensiHariIni->delete();

                            Absensi::create([
                                'siswa_id' => $item['siswa_id'],
                                'jadwal_id' => $record->id,
                                'diabsenkan_oleh_user_id' => $userId,
                                'status' => $item['status'],
                                'waktu_scan' => $now,
                            ]);
                        }

                        Notification::make()
                            ->title('Berhasil!')
                            ->success()
                            ->body('Absensi berhasil disimpan.')
                            ->send();
                    }),

                // Tables\Actions\Action::make('scanQR')
                //     ->label('Scan QR')
                //     ->icon('heroicon-o-qr-code')
                //     ->color('primary')
                //     ->action(function ($record, $data, $get) {
                //         // $data['qr_code'] hanya kalau pakai form, tapi di BarcodeScannerAction callback kita dapat $value
                //         // Jika pakai BarcodeScannerAction langsung, callback ada di ->afterScan()
                //     })
                //     ->afterScan(function ($record, $value) {
                //         // $value = QR code yang discan
                //         $siswa = \App\Models\Siswa::where('qr_code', $value)->first();

                //         if (!$siswa) {
                //             \Filament\Notifications\Notification::make()
                //                 ->title('Gagal!')
                //                 ->danger()
                //                 ->body('QR Code tidak valid: ' . $value)
                //                 ->send();
                //             return;
                //         }

                //         $sudahAbsen = \App\Models\Absensi::where('siswa_id', $siswa->id)
                //             ->where('jadwal_id', $record->id)
                //             ->whereDate('created_at', today())
                //             ->exists();

                //         if ($sudahAbsen) {
                //             \Filament\Notifications\Notification::make()
                //                 ->title('Perhatian!')
                //                 ->warning()
                //                 ->body($siswa->nama_siswa . ' sudah absen hari ini.')
                //                 ->send();
                //             return;
                //         }

                //         \App\Models\Absensi::create([
                //             'siswa_id' => $siswa->id,
                //             'jadwal_id' => $record->id,
                //             'diabsenkan_oleh_user_id' => auth()->id(),
                //             'status' => 'hadir',
                //             'waktu_scan' => now(),
                //         ]);

                //         \Filament\Notifications\Notification::make()
                //             ->title('Berhasil!')
                //             ->success()
                //             ->body('Absensi ' . $siswa->nama_siswa . ' berhasil disimpan.')
                //             ->send();
                //     })
                //     ->modalWidth('sm'), // optional, sesuaikan ukuran modal


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
            'index' => Pages\ListJadwals::route('/'),
            'create' => Pages\CreateJadwal::route('/create'),
            'edit' => Pages\EditJadwal::route('/{record}/edit'),
        ];
    }
}
