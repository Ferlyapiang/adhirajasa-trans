<?php

namespace App\Http\Controllers;

use App\Models\LogData;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class ReportLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        // Check if the request is for DataTables
        if (!$user ) {
            return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
        } else {
            if ($request->ajax()) {
                $logs = LogData::orderBy('created_at', 'desc')->get();
                return DataTables::of($logs)
                    ->addIndexColumn()
                    ->editColumn('created_at', function ($log) {
                        return $log->created_at->format('Y-m-d H:i:s');
                    })
                    ->make(true);
            }

            // Return the view if not an AJAX request
            return view('reports.logs.index');
        }
    }
}
