<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    function index(){
        $reports = Report::join('users', 'users.id', 'report.reporter_id')->get();
        return response()->json([
            "status" => 1,
            "message" => "Reports Fetched",
            "data" => $reports,
        ], 200);
    }

    function create(Request $request){
        $request->validate([
            'reporter_id' => 'required',
            'pickup_id' => 'required',
            'content' => 'required',
            'status' => 'required',
        ]);
        $report = new Report();
        $report->reporter_id = $request->reporter_id;
        $report->pickup_id = $request->pickup_id;
        $report->content = $request->content;
        $report->status = $request->status;
        $report->save();
        return response()->json([
            "status" => 1,
            "message" => "Saved Report",
            "data" => $report,
        ], 200);
    }
}
