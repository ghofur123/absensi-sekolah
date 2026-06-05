<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class GroupWhatsappList extends Page
{
    protected static ?string $navigationLabel = 'Group WhatsApp';
    protected static ?string $navigationIcon  = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $title           = 'Daftar Group WhatsApp';
    protected static ?string $navigationGroup = 'WhatsApp';

    protected static string $view = 'filament.resources.group-whatsapp-list-resource.pages.group-whatsapp-list';

    public array $groups = [];

    public function mount(): void
    {
        $token = 'r3UpUKoSLk17fkytNyMB';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/get-whatsapp-group',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $token,
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);

        // 🔴 INI YANG PALING PENTING
        // data group ada di dalam key "data"
        $this->groups = $result['data'] ?? [];
    }
}
