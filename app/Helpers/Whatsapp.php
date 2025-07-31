<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Whatsapp
{
    /**
     * Kirim pesan atau file via Fonnte WhatsApp
     *
     * @param string $target Nomor tujuan (format internasional, ex: 628123456789)
     * @param string|null $message Pesan teks (opsional jika kirim file)
     * @param string|null $filePath Path ke file lokal (opsional)
     * @param int $schedule Waktu kirim terjadwal (0 = langsung)
     * @return array
     */
    public static function send($target, $message = null, $filePath = null, $schedule = 0)
    {
        $postFields = [
            'target' => $target,
            'schedule' => $schedule,
            'countryCode' => '62',
            'typing' => false,
        ];

        if ($message) {
            $postFields['message'] = $message;
        }

        if ($filePath && file_exists($filePath)) {
            $postFields['file'] = new \CURLFile($filePath);
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . env('FONNTE_TOKEN'),
            ],
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        // === LOGGING SETIAP REQUEST ===
        Log::channel('whatsapp')->info('Fonnte WhatsApp Request', [
            'target' => $target,
            'message' => $message,
            'file' => $filePath,
            'schedule' => $schedule,
            'response_raw' => $response,
            'error' => $error,
        ]);

        if ($error) {
            return ['success' => false, 'error' => $error];
        }

        return ['success' => true, 'response' => json_decode($response, true)];
    }
}
