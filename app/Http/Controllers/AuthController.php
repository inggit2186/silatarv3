<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $user = User::query()
            ->where('email', $credentials['login'])
            ->orWhere('nomor_induk', $credentials['login'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => 'Email, nomor induk, atau password tidak sesuai.',
            ]);
        }

        Auth::login($user, (bool) ($credentials['remember'] ?? false));
        $request->session()->regenerate();

        return redirect()->intended(route('pelayanan'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'nip' => ['required', 'string', 'max:50'],
        ]);

        $user = User::where('nomor_induk', (string) $request->nip)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'NIP tidak ditemukan.',
            ]);
        }

        if (empty($user->telp)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp tidak tersedia. Hubungi administrator.',
            ]);
        }

        // Generate new password
        $date = Carbon::parse($user->tanggal_lahir)->format('d-m-y');
        $xdate = preg_replace('/[^0-9]/', '', $date);
        $newpass = 'ASN' . substr($user->nomor_induk, -3) . '' . $xdate;

        // Update password
        $user->password = Hash::make($newpass);
        $user->save();

        // Send WhatsApp message
        $textWA = "🔐 *SILATAR - RESET PASSWORD*\n\n"
            . "━━━━━━━━━━━━━━━━━━━━\n\n"
            . "📩 *Yth. Bpk/Ibu*\n"
            . "👤 *" . $user->name . "*\n\n"
            . "🔑 *Password akun Anda telah di-reset!*\n\n"
            . "━━━━━━━━━━━━━━━━━━━━\n\n"
            . "📧 *Email:*\n" . $user->email . "\n\n"
            . "🆔 *NIP:*\n" . $user->nomor_induk . "\n\n"
            . "🔐 *Password Baru:*\n" . $newpass . "\n\n"
            . "━━━━━━━━━━━━━━━━━━━━\n\n"
            . "📌 *Catatan:*\n"
            . "• Login dengan password baru\n"
            . "• Ganti password setelah login\n\n"
            . "━━━━━━━━━━━━━━━━━━━━\n\n"
            . "✨ _Silakan login dengan password baru_\n"
            . "🔗 Kemenagtanahdatar.id\n\n"
            . "_*_JFT Prakom Sekretariat_*_\n"
            . "© " . date('Y') . " SILATAR AI";

        $response = Http::post(env('URL_WA_SERVER') . "/send-message", [
            "api_key" => env('WA_TOKEN'),
            "sender" => env('WA_NUMBER'),
            "number" => "62" . $user->telp,
            "message" => $textWA,
            "footer" => "© " . date('Y') . " SILATAR AI"
        ]);

        $telp = preg_replace('/(?<=\d)(?=(\d{4})+$)/', ' ', $user->telp);

        return response()->json([
            'success' => true,
            'message' => 'Password Baru Anda telah dikirim ke no WhatsApp <br/> <b>+62 ' . $telp . '</b><hr/><i style="font-size: 13px">Jika Nomor WhatsApp Anda Salah, Silahkan WhatsApp ke nomor <br/><b>0895 0900 7078</b><br/>Dengan chat : <b>Set WhatsApp <i>NIP</i></b> dengan Nomor baru Anda.',
        ]);
    }
}
