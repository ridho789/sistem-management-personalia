<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class DailyReportManagementController extends Controller
{
    public function create() {
        $employee = Employee::all();
        return view('/backend/daily_report/form_daily_report', [
            'employee' => $employee
        ]);
    }
}
