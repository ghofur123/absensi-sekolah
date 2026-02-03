<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaSendResource\Pages;
use App\Filament\Resources\WaSendResource\RelationManagers;
use App\Http\Controllers\WhatsappFilamentController;
use App\Models\Jadwal;
use App\Models\JadwalTanggalStatusWa;
use App\Models\LembagaSetting;
use App\Models\WaSend;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

class WaSendResource extends Resource
{
    protected static ?string $model = Jadwal::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $navigationLabel = 'Kirim WA Absensi';
    protected static ?string $navigationGroup = 'Absensi & Kehadiran';
    protected static ?int $navigationSort = 40;

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
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('jadwalTanggalStatusWa.tanggal')
                    ->label('Tanggal Kirim')
                    ->date('d-m-Y')
                    ->placeholder('-'),
                TextColumn::make('jadwalTanggalStatusWa.waktu_kirim')
                    ->label('Dikirim')
                    ->since()
                    ->placeholder('Belum dikirim'),
                TextColumn::make('lembaga.nama_lembaga')
                    ->label('Lembaga'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),

                Action::make('absensi')
                    ->label('Kirim Whatsapp Orang tua')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('success')
                    ->modalHeading('Kirim Whatsapp Orang tua')
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

                                $kelasList = \App\Models\JadwalKelas::with('kelas')
                                    ->where('jadwal_id', $jadwalId)
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

                                            $status = $absensiHariIni[$siswa->id] ?? 'alpa';

                                            return [
                                                'siswa_id'   => $siswa->id,
                                                'nama_siswa' => $siswa->nama_siswa,
                                                'status'     => $status,
                                                'no_wa'      => $siswa->no_wa,
                                                'kirim_wa'   => $status === 'hadir' ? false : true,
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
                                                    ->dehydrated(true)
                                                    ->prefix(fn($get) => $get('nomor') . '.')
                                                    ->columnSpan(2),

                                                TextInput::make('status')
                                                    ->label(false)
                                                    ->disabled()
                                                    ->dehydrated(true)
                                                    ->formatStateUsing(fn($state) => strtoupper($state))
                                                    ->columnSpan(1),

                                                Toggle::make('kirim_wa')
                                                    ->label('Kirim WA')
                                                    ->columnSpan(1),

                                                TextInput::make('no_wa')
                                                    ->label(false)
                                                    ->disabled()
                                                    ->dehydrated(true)
                                                    ->columnSpan(1),
                                            ])
                                            ->columns(5)
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

                        $whatsappSetting = \App\Models\LembagaSetting::first();

                        foreach ($data['daftar_absensi'] as $kelas) {
                            foreach ($kelas['siswa'] as $item) {

                                // =========================
                                // SIMPAN / UPDATE ABSENSI
                                // =========================
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
                                }

                                // =========================
                                // KIRIM WHATSAPP
                                // =========================
                                if (
                                    $item['kirim_wa'] === true
                                    && ! empty($item['no_wa'])
                                    && strlen($item['no_wa']) >= 10
                                ) {

                                    WhatsappFilamentController::kirimAbsensi(
                                        $item['no_wa'],
                                        $item['nama_siswa'],
                                        $item['status'],
                                        $record->lembaga_id
                                    );
                                }
                            }
                        }

                        // =========================
                        // TANDA SUDAH DIKIRIM
                        // =========================
                        \App\Models\JadwalTanggalStatusWa::updateOrCreate(
                            [
                                'jadwal_id' => $record->id,
                                'tanggal' => today(),
                            ],
                            [
                                'sudah_dikirim' => true,
                                'waktu_kirim' => now(),
                                'dikirim_oleh_user_id' => $userId,
                                'keterangan' => 'Absensi harian',
                            ]
                        );

                        Notification::make()
                            ->title('Berhasil!')
                            ->success()
                            ->body('Absensi berhasil disimpan & WhatsApp diproses.')
                            ->send();
                    }),
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
            'index' => Pages\ListWaSends::route('/'),
            'create' => Pages\CreateWaSend::route('/create'),
            'edit' => Pages\EditWaSend::route('/{record}/edit'),
        ];
    }
}
