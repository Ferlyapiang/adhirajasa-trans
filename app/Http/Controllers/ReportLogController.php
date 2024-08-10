<?php

namespace App\Http\Controllers;

use App\Models\LogData;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReportLogController extends Controller
{
    public function index(Request $request)
    {
        // Check if the request is for DataTables
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
        return view('admin.reports.index');
    }
}
