<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AcaraController extends Controller
{
    /**
     * Display a listing of acara.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = DB::table('ktd_acara');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('lokasi', 'LIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $acaraList = $query->orderBy('tanggal', 'desc')->paginate(15);

        return view('admin.acara.index', compact('acaraList', 'search', 'status'));
    }

    /**
     * Show the form for creating a new acara.
     */
    public function create()
    {
        return view('admin.acara.create');
    }

    /**
     * Store a newly created acara.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesei' => 'required',
            'lokasi' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|integer|min:0',
            'status' => 'required|in:active,completed,cancelled',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Handle foto upload with compression
        $filename = null;
        if ($request->hasFile('foto')) {
            $filename = 'acara_' . time() . '_' . $request->file('foto')->getClientOriginalName();
            $this->compressAndStorePhoto($request->file('foto'), $filename);
        }

        $insertData = [
            'dept_id' => $request->input('dept_id', 0),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesei' => $request->jam_selesei,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'status' => $request->status,
            'filename' => $filename,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        \Log::info('Inserting acara', $insertData);

        try {
            DB::table('ktd_acara')->insert($insertData);
            return redirect()->route('admin.acara')
                ->with('success', 'Acara berhasil dibuat');
        } catch (\Exception $e) {
            \Log::error('Failed to insert acara', ['error' => $e->getMessage()]);
            return redirect()->route('admin.acara.create')
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified acara.
     */
    public function show($id, Request $request)
    {
        $acara = DB::table('ktd_acara')->where('id', $id)->first();

        if (!$acara) {
            return redirect()->route('admin.acara')
                ->with('error', 'Acara tidak ditemukan');
        }

        $search = $request->input('search');
        $statusFilter = $request->input('status_filter');
        $deptFilter = $request->input('dept_filter');

        // Get departments for filter (status 1 and 2 only)
        $departments = DB::table('ktd_department')
            ->whereIn('status', [1, 2])
            ->orderBy('nama')
            ->get();

        // Get attendance list with filters
        $query = DB::table('ktd_presensi_acara')
            ->where('ktd_presensi_acara.acara_id', $id)
            ->join('users', 'ktd_presensi_acara.user_nip', '=', 'users.nomor_induk')
            ->leftJoin('ktd_department', 'users.dept_id', '=', 'ktd_department.id')
            ->select('ktd_presensi_acara.*', 'users.name', 'users.telp', 'ktd_department.nama as unit_kerja');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'LIKE', "%{$search}%")
                  ->orWhere('ktd_presensi_acara.user_nip', 'LIKE', "%{$search}%");
            });
        }

        if ($statusFilter) {
            $query->where('ktd_presensi_acara.status', $statusFilter);
        }

        if ($deptFilter) {
            $query->where('users.dept_id', $deptFilter);
        }

        $attendance = $query->orderBy('ktd_presensi_acara.created_at', 'desc')->get();

        $hadirCount = $attendance->where('status', 'hadir')->count();
        $tidakHadirCount = $attendance->where('status', 'tidak_hadir')->count();

        return view('admin.acara.show', compact('acara', 'attendance', 'hadirCount', 'tidakHadirCount', 'departments'));
    }

    /**
     * Show the form for editing the specified acara.
     */
    public function edit($id)
    {
        $acara = DB::table('ktd_acara')->where('id', $id)->first();

        if (!$acara) {
            return redirect()->route('admin.acara')
                ->with('error', 'Acara tidak ditemukan');
        }

        return view('admin.acara.edit', compact('acara'));
    }

    /**
     * Update the specified acara.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesei' => 'required',
            'lokasi' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|integer|min:0',
            'status' => 'required|in:active,completed,cancelled',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Handle foto upload
        $filename = null;
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            $oldAcara = DB::table('ktd_acara')->where('id', $id)->first();
            if ($oldAcara && $oldAcara->filename) {
                Storage::disk('public')->delete('acara/' . $oldAcara->filename);
            }

            $filename = 'acara_' . time() . '_' . $request->file('foto')->getClientOriginalName();
            $this->compressAndStorePhoto($request->file('foto'), $filename);
        } else {
            // Keep existing filename
            $oldAcara = DB::table('ktd_acara')->where('id', $id)->first();
            $filename = $oldAcara ? $oldAcara->filename : null;
        }

        $updateData = [
            'dept_id' => $request->input('dept_id', 0),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesei' => $request->jam_selesei,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'status' => $request->status,
            'filename' => $filename,
            'updated_at' => now(),
        ];

        \Log::info('Updating acara', ['id' => $id, 'filename' => $filename, 'has_file' => $request->hasFile('foto')]);

        try {
            DB::table('ktd_acara')
                ->where('id', $id)
                ->update($updateData);

            return redirect()->route('admin.acara')
                ->with('success', 'Acara berhasil diupdate');
        } catch (\Exception $e) {
            \Log::error('Failed to update acara', ['error' => $e->getMessage()]);
            return redirect()->route('admin.acara.edit', $id)
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified acara.
     */
    public function destroy($id)
    {
        DB::table('ktd_acara')->where('id', $id)->delete();

        return redirect()->route('admin.acara')
            ->with('success', 'Acara berhasil dihapus');
    }

    /**
     * Compress and store photo using GD library
     */
    private function compressAndStorePhoto($file, $filename)
    {
        try {
            // Get image info
            $imageInfo = getimagesize($file->getRealPath());

            if (!$imageInfo) {
                // Not a valid image, store original
                $file->storeAs('public/acara', $filename);
                return true;
            }

            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $mime = $imageInfo['mime'];

            // Create image resource based on mime type
            $image = null;
            switch ($mime) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($file->getRealPath());
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($file->getRealPath());
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($file->getRealPath());
                    break;
            }

            if (!$image) {
                $file->storeAs('public/acara', $filename);
                return true;
            }

            // Resize to max 1200px width
            $maxWidth = 1200;
            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int)($height * ($maxWidth / $width));
                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($image);
                $image = $resized;
            }

            // Store to disk with 80% quality
            $path = storage_path('app/public/acara');
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            imagejpeg($image, $path . '/' . $filename, 80);
            imagedestroy($image);

            return true;

        } catch (\Exception $e) {
            \Log::error('Failed to compress photo: ' . $e->getMessage());
            // Fallback: store original if compression fails
            $file->storeAs('public/acara', $filename);
            return true;
        }
    }
}
