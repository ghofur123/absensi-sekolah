<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LembagaSettingResource\Pages;
use App\Filament\Resources\LembagaSettingResource\RelationManagers;
use App\Models\LembagaSetting;
use App\Models\WaTemplate;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LembagaSettingResource extends Resource
{
    protected static ?string $model = LembagaSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan Lembaga';

    protected static ?string $navigationGroup = 'Manajemen Lembaga';

    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Lembaga')
                    ->schema([
                        Select::make('lembaga_id')
                            ->relationship('lembaga', 'nama_lembaga')
                            ->required(),
                    ]),

                Section::make('Pengaturan WhatsApp Absensi')
                    ->schema([
                        Toggle::make('wa_absensi_enabled')
                            ->label('Aktifkan WhatsApp Absensi')
                            ->live(), // ⬅️ PENTING

                        TextInput::make('fonnte_token')
                            ->label('Token Fonnte')
                            ->password()
                            ->revealable()
                            ->visible(fn(Get $get) => $get('wa_absensi_enabled'))
                            ->required(fn(Get $get) => $get('wa_absensi_enabled')),

                    ]),

                Section::make('Status Absensi yang Dikirimi Pesan')
                    ->description('Pilih status absensi yang akan dikirim ke WhatsApp secara langsung di scan oleh guru tanpa menu Kirim WA Absensi')
                    ->schema([
                        Toggle::make('kirim_hadir')
                            ->label('Hadir')
                            ->helperText(
                                fn(Get $get) =>
                                $get('kirim_hadir')
                                    ? '✅ WA dikirim saat HADIR'
                                    : '❌ WA tidak dikirim saat HADIR'
                            ),

                        Toggle::make('kirim_izin')
                            ->label('Izin')
                            ->helperText(
                                fn(Get $get) =>
                                $get('kirim_izin')
                                    ? '✅ WA dikirim saat IZIN'
                                    : '❌ WA tidak dikirim saat IZIN'
                            ),

                        Toggle::make('kirim_sakit')
                            ->label('Sakit')
                            ->helperText(
                                fn(Get $get) =>
                                $get('kirim_sakit')
                                    ? '✅ WA dikirim saat SAKIT'
                                    : '❌ WA tidak dikirim saat SAKIT'
                            ),

                        Toggle::make('kirim_alpa')
                            ->label('Alpa')
                            ->helperText(
                                fn(Get $get) =>
                                $get('kirim_alpa')
                                    ? '⚠️ WA dikirim saat ALPA (anak tidak hadir)'
                                    : '❌ WA tidak dikirim saat ALPA'
                            ),
                    ])
                    ->columns(2)
                    ->visible(fn(Get $get) => $get('wa_absensi_enabled')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lembaga.nama_lembaga')
                    ->label('Lembaga')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('wa_absensi_enabled')
                    ->label('WA Aktif')
                    ->boolean(),

                IconColumn::make('kirim_hadir')->label('Hadir')->boolean(),
                IconColumn::make('kirim_izin')->label('Izin')->boolean(),
                IconColumn::make('kirim_sakit')->label('Sakit')->boolean(),
                IconColumn::make('kirim_alpa')->label('Alpa')->boolean(),

                TextColumn::make('updated_at')
                    ->label('Update')
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('waTemplate')
                    ->label('WA Template')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')

                    ->form([

                        // =========================
                        // TEMPLATE WA ORANG TUA
                        // =========================
                        Section::make('Template WhatsApp Orang Tua')
                            ->description('Header & footer untuk pesan WA ke orang tua/wali')
                            ->schema([

                                Toggle::make('aktif_orang_tua')
                                    ->label('Aktifkan WA ke Orang Tua')
                                    ->default(true),

                                Textarea::make('header_orang_tua')
                                    ->label('Header Pesan')
                                    ->rows(3)
                                    ->placeholder('Yth. Orang Tua/Wali Murid'),

                                Textarea::make('footer_orang_tua')
                                    ->label('Footer Pesan')
                                    ->rows(3)
                                    ->placeholder("Terima kasih\nSD ABC"),

                            ])
                            ->collapsible(),

                        // =========================
                        // TEMPLATE WA GURU
                        // =========================
                        Section::make('Template WhatsApp Guru')
                            ->description('Header & footer untuk pesan WA ke guru')
                            ->schema([

                                Toggle::make('aktif_guru')
                                    ->label('Aktifkan WA ke Guru')
                                    ->default(true),

                                Textarea::make('header_guru')
                                    ->label('Header Pesan')
                                    ->rows(3)
                                    ->placeholder('Yth. Bapak/Ibu Guru'),

                                Textarea::make('footer_guru')
                                    ->label('Footer Pesan')
                                    ->rows(3)
                                    ->placeholder("Salam Hormat\nKepala Sekolah"),

                            ])
                            ->collapsible(),
                    ])

                    // =========================
                    // ISI FORM SAAT MODAL DIBUKA
                    // =========================
                    ->mountUsing(function ($form, $record) {

                        $template = WaTemplate::firstOrNew([
                            'lembaga_id' => $record->lembaga_id,
                        ]);

                        $form->fill([
                            'header_orang_tua' => $template->header_orang_tua,
                            'footer_orang_tua' => $template->footer_orang_tua,
                            'aktif_orang_tua'  => $template->aktif_orang_tua ?? true,

                            'header_guru' => $template->header_guru,
                            'footer_guru' => $template->footer_guru,
                            'aktif_guru'  => $template->aktif_guru ?? true,
                        ]);
                    })

                    // =========================
                    // SIMPAN DATA
                    // =========================
                    ->action(function ($record, array $data) {

                        WaTemplate::updateOrCreate(
                            ['lembaga_id' => $record->lembaga_id],
                            [
                                'header_orang_tua' => $data['header_orang_tua'],
                                'footer_orang_tua' => $data['footer_orang_tua'],
                                'aktif_orang_tua'  => $data['aktif_orang_tua'] ?? false,

                                'header_guru' => $data['header_guru'],
                                'footer_guru' => $data['footer_guru'],
                                'aktif_guru'  => $data['aktif_guru'] ?? false,
                            ]
                        );
                    })

                    ->modalHeading('Template WhatsApp Absensi')
                    ->modalSubmitActionLabel('Simpan Template')
                    ->modalWidth('xl'),
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
            'index' => Pages\ListLembagaSettings::route('/'),
            'create' => Pages\CreateLembagaSetting::route('/create'),
            'edit' => Pages\EditLembagaSetting::route('/{record}/edit'),
        ];
    }
}
