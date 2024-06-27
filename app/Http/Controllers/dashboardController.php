<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\DataLeave;
use App\Models\Divisi;
use App\Models\Position;
use App\Models\Company;
use App\Models\StatusEmployee;
use App\Models\TypeLeave;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index() {
        $employeeActive = Employee::where('is_active', true)->count();
        $employeeInactive = Employee::where('is_active', false)->count();

        $positions = Position::pluck('nama_jabatan', 'id_jabatan');
        $divisions = Divisi::pluck('nama_divisi', 'id_divisi');
        $companies = Company::pluck('nama_perusahaan', 'id_perusahaan');
        $statuses = StatusEmployee::pluck('nama_status', 'id_status');

        $employee = Employee::pluck('nama_karyawan', 'id_karyawan');
        $idcard = Employee::pluck('id_card', 'id_karyawan');
        $typeleave = TypeLeave::pluck('nama_tipe_cuti', 'id_tipe_cuti');

        $dataLeaveToApproved = DataLeave::where('status_cuti', 'To Approved')
            ->whereIn('id_karyawan', Employee::where('is_active', true)->pluck('id_karyawan'))
            ->get();

        $countDataLeaveToApproved = $dataLeaveToApproved->count();

        $employeeExpiredContract = Employee::where('is_active', true)
            ->whereDate('akhir_masa_kontrak', '<=', Carbon::now()->addDays(30))
            ->orderBy('nama_karyawan', 'asc')
            ->get();

        $countEmployeeExpiredContract = $employeeExpiredContract->count();

        return view('/frontend/dashboard', [
            'employee' => $employee,
            'idcard' => $idcard,
            'typeleave' => $typeleave,
            'employeeActive' => $employeeActive,
            'employeeInactive' => $employeeInactive,
            'positions' => $positions,
            'divisions' => $divisions,
            'companies' => $companies,
            'statuses' => $statuses,
            'dataLeaveToApproved' => $dataLeaveToApproved,
            'employeeExpiredContract' => $employeeExpiredContract,
            'countEmployeeExpiredContract' => $countEmployeeExpiredContract,
            'countDataLeaveToApproved' => $countDataLeaveToApproved
        ]);
    }
}
