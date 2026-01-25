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
                    ->schema([
                        Toggle::make('kirim_hadir')->label('Hadir'),
                        Toggle::make('kirim_izin')->label('Izin'),
                        Toggle::make('kirim_sakit')->label('Sakit'),
                        Toggle::make('kirim_alpa')->label('Alpa'),
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
                        Toggle::make('aktif')
                            ->label('Aktifkan Template')
                            ->default(true),

                        Textarea::make('header')
                            ->label('Header Pesan')
                            ->rows(4)
                            ->placeholder("📢 Informasi Absensi\n{{nama_lembaga}}"),

                        Textarea::make('footer')
                            ->label('Footer Pesan')
                            ->rows(3)
                            ->placeholder("Terima kasih 🙏\n— {{nama_lembaga}}"),
                    ])

                    ->mountUsing(function ($form, $record) {
                        $template = WaTemplate::firstOrNew([
                            'lembaga_id' => $record->lembaga_id,
                        ]);

                        $form->fill([
                            'header' => $template->header,
                            'footer' => $template->footer,
                            'aktif'  => $template->aktif ?? true,
                        ]);
                    })

                    ->action(function ($record, array $data) {
                        WaTemplate::updateOrCreate(
                            ['lembaga_id' => $record->lembaga_id],
                            [
                                'header' => $data['header'],
                                'footer' => $data['footer'],
                                'aktif'  => $data['aktif'],
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
