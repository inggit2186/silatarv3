<?php

namespace App\Commands\Webhook;

use App\Models\Department;
use App\Models\User;
use App\Services\WhatsAppService;

class CekSatkerCommand extends BaseCommand
{
    private const MIN_NAME_LENGTH = 4;

    public function execute(): ?array
    {
        $name = $this->message;

        if (strlen($name) < self::MIN_NAME_LENGTH) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->validationError('Mohon Masukkan Nama Satker minimal 4 Huruf')
            );
        }

        // Escape special LIKE characters
        $searchName = WhatsAppService::escapeLikeQuery($name);

        $satker = Department::where('nama', 'LIKE', '%' . $searchName . '%')
            ->orWhere('kode', 'LIKE', '%' . $searchName . '%')
            ->with('instansi')
            ->first();

        if (!$satker) {
            return $this->waService->sendMessage(
                $this->phoneNumber,
                $this->notFound('Unit Kerja')
            );
        }

        // Count employees
        $jmlPegawai = User::where('dept_id', (string) $satker->id)->count();

        // Find kepala (head) of unit
        $kepala = User::where('dept_id', (string) $satker->id)
            ->where(function ($query) {
                $query->where('kat_jabatan', 'kasubbag')
                    ->orWhere('kat_jabatan', 'kasi')
                    ->orWhere('kat_jabatan', 'kepala');
            })
            ->first();

        $namaKepala = $kepala?->name ?? '-';

        // Get address info from related table
        $alamat = $satker->instansi?->alamat ?? '-';
        $email = $satker->instansi?->email ?? '-';
        $kontak = $satker->instansi?->no_kontak ?? '-';

        // Format phone number for display
        if ($kontak !== '-') {
            $kontak = WhatsAppService::formatPhoneForDisplay($kontak);
        }

        $url = "https://silatar.kemenag.go.id/v2/Satker/" . $satker->id;

        $textWA = "*:: SILATAR AI-CHAT ::*\n\n"
            . "Unit Kerja : *{$satker->nama}* \n"
            . "Kepala Satker : *{$namaKepala}* \n\n"
            . "Alamat : *{$alamat}* \n"
            . "Email : *{$email}* \n"
            . "No Kontak : *{$kontak}*\n\n"
            . "Jumlah Pegawai : *{$jmlPegawai} Orang* \n\n"
            . "Untuk List Pegawai, please click link-dessous ini \n"
            . "*{$url}* \n\n\n"
            . "_Hormat Kami,_\n\n"
            . "_*SILATAR AI*_";

        return $this->waService->sendMessage($this->phoneNumber, $textWA);
    }
}
