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
                    ->label('Absensi')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('success')
                    ->modalHeading('Absensi Siswa')
                    ->modalWidth('7xl')
                    ->fillForm(function ($record) {
                        return [
                            'lembaga_id' => $record->lembaga_id,
                            'kelas_id' => $record->kelas_id,
                            'jadwal_id' => $record->id,
                            'daftar_absensi' => [], // Kosongkan dulu, akan diisi setelah pilih kelas
                        ];
                    })
                    ->form([
                        Select::make('lembaga_id')
                            ->label('Lembaga')
                            ->options(Lembaga::pluck('nama_lembaga', 'id'))
                            ->required()
                            ->dehydrated()
                            ->live(),

                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(
                                fn(callable $get) =>
                                Kelas::where('lembaga_id', $get('lembaga_id'))
                                    ->pluck('nama_kelas', 'id')
                            )
                            ->required()
                            ->live()
                            ->dehydrated(),

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
                            ->defaultItems(0),
                    ])
                    ->action(function ($record, $data) {
                        $userId = Auth::id();
                        $now = Carbon::now();

                        // Hapus absensi hari ini
                        Absensi::where('jadwal_id', $record->id)
                            ->whereDate('created_at', today())
                            ->delete();

                        // Simpan absensi baru
                        foreach ($data['daftar_absensi'] ?? [] as $item) {
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

                Action::make('scanQR')
                    ->label('Scan QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('primary')
                    ->modalHeading('Scan QR Code Siswa')
                    ->modalWidth('lg')
                    ->modalContent(fn($record) => view(
                        'filament.absensi.scan-qr',
                        ['jadwal' => $record]
                    )),

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
