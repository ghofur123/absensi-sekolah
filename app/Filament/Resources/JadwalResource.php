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
use Filament\Forms\Components\Section;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use ZipStream\Time;

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
                    ->label('Lembaga')
                    ->relationship('lembaga', 'nama_lembaga')
                    ->required()
                    ->reactive(), // penting agar select lain bisa merespon perubahan

                Select::make('guru_id')
                    ->label('Guru')
                    ->required()
                    ->options(function ($get) {
                        $lembagaId = $get('lembaga_id');
                        if (!$lembagaId) {
                            return [];
                        }
                        return \App\Models\Guru::where('lembaga_id', $lembagaId)
                            ->pluck('nama', 'id');
                    })
                    ->disabled(fn($get) => !$get('lembaga_id')),

                Select::make('mata_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->required()
                    ->options(function ($get) {
                        $lembagaId = $get('lembaga_id');

                        if (! $lembagaId) {
                            return [];
                        }

                        return \App\Models\MataPelajaran::where('lembaga_id', $lembagaId)
                            ->with('lembaga')
                            ->get()
                            ->mapWithKeys(function ($mapel) {
                                return [
                                    $mapel->id => $mapel->nama_mapel . ' — ' . $mapel->lembaga->nama_lembaga,
                                ];
                            });
                    })
                    ->disabled(fn($get) => ! $get('lembaga_id')),

                CheckboxList::make('hari')
                    ->label('Hari Mengajar')
                    ->options([
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                        'sabtu' => 'Sabtu',
                        'Ahad' => 'Ahad',
                    ])
                    ->columns(3)
                    ->required(),

                TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->required()
                    ->reactive(),

                TextInput::make('batas_awal')
                    ->label('Batas Awal (menit)')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->disabled(fn(callable $get) => blank($get('jam_mulai')))
                    ->hint('Jam mulai - batas awal')
                    ->suffix(function (callable $get) {
                        if (blank($get('jam_mulai')) || blank($get('batas_awal'))) {
                            return null;
                        }

                        return Carbon::parse($get('jam_mulai'))
                            ->subMinutes((int) $get('batas_awal'))
                            ->format('H:i');
                    }),

                TimePicker::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->required()
                    ->reactive(),

                TextInput::make('batas_pas')
                    ->label('Batas Pas (menit)')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->disabled(fn(callable $get) => blank($get('jam_selesai')))
                    ->hint('Jam selesai + batas pas')
                    ->suffix(function (callable $get) {
                        if (blank($get('jam_selesai')) || blank($get('batas_pas'))) {
                            return null;
                        }

                        return Carbon::parse($get('jam_selesai'))
                            ->addMinutes((int) $get('batas_pas'))
                            ->format('H:i');
                    }),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('hari')
                //     ->badge()
                //     ->sortable(),

                TextColumn::make('jam')
                    ->label('Jam')
                    ->getStateUsing(function ($record) {

                        $mulai   = \Carbon\Carbon::parse($record->jam_mulai);
                        $selesai = \Carbon\Carbon::parse($record->jam_selesai);

                        $bukaAbsensi = $mulai->copy()->subMinutes($record->batas_awal)->format('H:i');
                        $batasPas    = $mulai->copy()->addMinutes($record->batas_pas)->format('H:i');

                        return "
                            <div class='text-center text-sm leading-tight'>
                                <div><strong>Jadwal</strong></div>
                                <div>{$mulai->format('H:i')} - {$selesai->format('H:i')}</div>

                                <hr class='my-1'>

                                <div class='text-xs text-gray-600'>
                                    <div>Buka Absensi : {$bukaAbsensi}</div>
                                    <div>Tepat Waktu : ≤ {$batasPas}</div>
                                </div>
                            </div>
                        ";
                    })
                    ->html(),


                TextColumn::make('mataPelajaran.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                TextColumn::make('kelas')
                    ->label('Kelas')
                    ->badge()
                    ->getStateUsing(
                        fn($record) =>
                        $record->kelases->pluck('nama_kelas')->toArray()
                    ),

                TextColumn::make('guru.nama')
                    ->label('Guru')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('lembaga.nama_lembaga')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Lembaga'),
            ])
            ->filters([
                SelectFilter::make('hari')
                    ->label('Hari')
                    ->options([
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                        'sabtu' => 'Sabtu',
                    ])
                    ->default(function () {
                        return match (now()->timezone('Asia/Jakarta')->dayOfWeek) {
                            1 => 'senin',
                            2 => 'selasa',
                            3 => 'rabu',
                            4 => 'kamis',
                            5 => 'jumat',
                            6 => 'sabtu',
                            default => null,
                        };
                    })
                    ->query(function ($query, $data) {
                        if (! $data['value']) {
                            return $query;
                        }

                        return $query->whereJsonContains('hari', $data['value']);
                    }),

                SelectFilter::make('lembaga')
                    ->relationship('lembaga', 'nama_lembaga'),
                Filter::make('jam_aktif')
                    ->label('Jam Aktif')
                    ->toggle()
                    ->default(true)
                    ->query(function (Builder $query) {

                        $now = Carbon::now()->format('H:i:s');

                        $query
                            ->whereTime('jam_mulai', '<=', Carbon::parse($now)->addHour())
                            ->whereTime('jam_selesai', '>=', Carbon::parse($now)->subHour());
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('atur_kelas')
                    ->label('Kelas')
                    ->icon('heroicon-o-academic-cap')
                    ->color('info')
                    ->modalHeading('Pilih Kelas yang Mengikuti Jadwal')
                    ->modalWidth('lg')
                    ->fillForm(function ($record) {
                        return [
                            'kelas_ids' => $record->kelases()->pluck('kelas.id')->toArray(),
                        ];
                    })
                    ->form([
                        CheckboxList::make('kelas_ids')
                            ->label('Daftar Kelas')
                            ->options(function ($record) {
                                return Kelas::where('lembaga_id', $record->lembaga_id)
                                    ->pluck('nama_kelas', 'id');
                            })
                            ->columns(2)
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        $record->kelases()->sync($data['kelas_ids']);

                        Notification::make()
                            ->title('Berhasil')
                            ->success()
                            ->body('Kelas berhasil diperbarui.')
                            ->send();
                    }),
                Action::make('cetak_kartu')
                    ->label('QR Guru')
                    ->icon('heroicon-o-printer')
                    ->color('danger')
                    ->url(fn($record) => route('qr.absen.guru.pdf', $record->id))
                    ->openUrlInNewTab(),

                Action::make('absensi')
                    ->label('Absensi')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('success')
                    ->modalHeading('Absensi Siswa')
                    ->modalWidth('7xl')

                    ->fillForm(function ($record) {
                        return [
                            'jadwal_id' => $record->id,
                            'daftar_absensi' => [],
                        ];
                    })

                    ->form([

                        Hidden::make('jadwal_id'),

                        Repeater::make('daftar_absensi')
                            ->label(false)
                            ->afterStateHydrated(function (
                                \Filament\Forms\Set $set,
                                \Filament\Forms\Get $get
                            ) {
                                if (! empty($get('daftar_absensi'))) {
                                    return;
                                }

                                $jadwalId = $get('jadwal_id');
                                if (! $jadwalId) {
                                    return;
                                }

                                $jadwal = \App\Models\Jadwal::findOrFail($jadwalId);

                                $kelasList = \App\Models\JadwalKelas::with(['kelas'])
                                    ->where('jadwal_id', $jadwalId)
                                    ->whereHas('kelas', function ($q) use ($jadwal) {
                                        $q->where('lembaga_id', $jadwal->lembaga_id);
                                    })
                                    ->get();

                                $absensiHariIni = \App\Models\Absensi::where('jadwal_id', $jadwalId)
                                    ->whereDate('created_at', today())
                                    ->pluck('status', 'siswa_id')
                                    ->toArray();

                                $result = [];

                                foreach ($kelasList as $pivot) {

                                    $siswas = \App\Models\Siswa::where('kelas_id', $pivot->kelas_id)
                                        ->get()
                                        ->map(function ($siswa, $i) use ($absensiHariIni) {
                                            return [
                                                'siswa_id'   => $siswa->id,
                                                'nama_siswa' => $siswa->nama_siswa,
                                                'status'     => $absensiHariIni[$siswa->id] ?? 'alpa',
                                                'nomor'      => $i + 1,
                                            ];
                                        })
                                        ->toArray();

                                    $result[] = [
                                        'kelas_id'   => $pivot->kelas_id,
                                        'nama_kelas' => $pivot->kelas->nama_kelas,
                                        'siswa'      => $siswas,
                                    ];
                                }

                                $set('daftar_absensi', $result);
                            })

                            ->schema([

                                Hidden::make('kelas_id'),
                                Hidden::make('nama_kelas'),

                                Section::make(fn($get) => 'Kelas ' . $get('nama_kelas'))
                                    ->collapsible()
                                    ->schema([

                                        Repeater::make('siswa')
                                            ->label(false)
                                            ->schema([

                                                Hidden::make('siswa_id'),
                                                Hidden::make('nomor'),

                                                TextInput::make('nama_siswa')
                                                    ->label(false)
                                                    ->disabled()
                                                    ->prefix(fn($get) => $get('nomor') . '.')
                                                    ->columnSpan(1),

                                                ToggleButtons::make('status')
                                                    ->label(false)
                                                    ->options([
                                                        'hadir' => 'H',
                                                        'izin'  => 'I',
                                                        'sakit' => 'S',
                                                        'alpa'  => 'A',
                                                    ])
                                                    ->icons([
                                                        'hadir' => 'heroicon-o-check-circle',
                                                        'izin'  => 'heroicon-o-document-text',
                                                        'sakit' => 'heroicon-o-face-frown',
                                                        'alpa'  => 'heroicon-o-x-circle',
                                                    ])
                                                    ->colors([
                                                        'hadir' => 'success',
                                                        'izin'  => 'info',
                                                        'sakit' => 'warning',
                                                        'alpa'  => 'danger',
                                                    ])
                                                    ->inline()
                                                    ->grouped()
                                                    ->columnSpan(2),
                                            ])
                                            ->columns(3)
                                            ->deletable(false)
                                            ->addable(false)
                                            ->reorderable(false),
                                    ]),
                            ])
                            ->deletable(false)
                            ->addable(false)
                            ->reorderable(false),
                    ])

                    ->action(function ($record, $data) {

                        $userId = Auth::id();
                        $now    = now();

                        foreach ($data['daftar_absensi'] as $kelas) {
                            foreach ($kelas['siswa'] as $item) {

                                $absensi = \App\Models\Absensi::where('jadwal_id', $record->id)
                                    ->where('siswa_id', $item['siswa_id'])
                                    ->whereDate('created_at', today())
                                    ->first();

                                if (! $absensi) {
                                    \App\Models\Absensi::create([
                                        'siswa_id' => $item['siswa_id'],
                                        'jadwal_id' => $record->id,
                                        'diabsenkan_oleh_user_id' => $userId,
                                        'status' => $item['status'],
                                        'waktu_scan' => $now,
                                    ]);
                                    continue;
                                }

                                if ($absensi->status === $item['status']) {
                                    continue;
                                }

                                $absensi->delete();

                                \App\Models\Absensi::create([
                                    'siswa_id' => $item['siswa_id'],
                                    'jadwal_id' => $record->id,
                                    'diabsenkan_oleh_user_id' => $userId,
                                    'status' => $item['status'],
                                    'waktu_scan' => $now,
                                ]);
                            }
                        }

                        Notification::make()
                            ->title('Berhasil!')
                            ->success()
                            ->body('Absensi berhasil disimpan.')
                            ->send();
                    }),

                Action::make('scan_qr')
                    ->label('Scan Siswa')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->url(fn($record) => route('scan.jadwal', $record))
                    ->openUrlInNewTab(),


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
