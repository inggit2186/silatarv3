<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SatkerPemberkasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TpgVerificationController extends Controller
{
    protected ?string $userRole = null;
    protected ?int $userDeptId = null;

    public function __construct()
    {
        $user = Auth::user();
        $this->userRole = $user?->role;
        $this->userDeptId = $user?->dept_id;
    }

    /**
     * Check if user has access to this service
     */
    protected function hasAccess(?int $serviceDeptId): bool
    {
        if ($this->userRole === 'admin') {
            return true;
        }

        // Petugas hanya bisa akses layanan sesuai dept_id
        if ($this->userRole === 'petugas') {
            return $this->userDeptId == $serviceDeptId;
        }

        return false;
    }

    /**
     * Filter by dept_id untuk petugas non-admin
     */
    protected function filterByDeptAccess($query, ?int $deptId = null)
    {
        if ($this->userRole === 'admin') {
            if ($deptId) {
                $query->where('dept_id', $deptId);
            }
            return $query;
        }

        // Petugas terbatas di dept_id sendiri
        return $query->where('dept_id', $this->userDeptId);
    }

    /**
     * Get tipe label for display
     */
    protected function getTipeLabel(string $tipe): string
    {
        return match ($tipe) {
            'PAIS-TPG-SEMESTER' => 'TPG Semester',
            'PAIS-TPG-BULANAN' => 'TPG Bulanan',
            'PENMAD-TPG-BULANAN' => 'PENMAD TPG Bulanan',
            'PENMAD-PENGAWAS-BULANAN' => 'PENMAD Pengawas Bulanan',
            default => $tipe,
        };
    }

    /**
     * Get noreq prefix untuk filter
     */
    protected function getNoreqPrefix(?string $tipe = null): string
    {
        return match ($tipe) {
            'semester' => 'PAIS-TPG-SEMESTER',
            'bulanan' => 'PAIS-TPG-BULANAN',
            'penmad' => 'PENMAD-TPG-BULANAN',
            'pengawas' => 'PENMAD-PENGAWAS-BULANAN',
            default => '',
        };
    }
}
