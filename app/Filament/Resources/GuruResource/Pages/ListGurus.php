<?php

namespace App\Filament\Resources\GuruResource\Pages;

use App\Filament\Resources\GuruResource;
use App\Imports\GuruImport;
use App\Models\Guru;
use App\Models\Lembaga;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListGurus extends ListRecords
{
    protected static string $resource = GuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importGuru')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->slideOver()

                ->form([
                    Select::make('lembaga_id')
                        ->label('Lembaga')
                        ->options(Lembaga::pluck('nama_lembaga', 'id'))
                        ->required(),

                    FileUpload::make('file')
                        ->label('File Excel')
                        ->required()
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->disk('local'), // penting
                ])

                ->action(function (array $data) {
                    Excel::import(
                        new GuruImport($data['lembaga_id']),
                        Storage::disk('local')->path($data['file'])
                    );
                }),
            Actions\CreateAction::make(),
            Action::make('downloadTemplate')
                ->label('Download Template')
                ->url(url('/download-template/guru'))
                ->openUrlInNewTab(),
        ];
    }
}
