<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Process;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class ProcessCsvDownloadsController extends Controller
{
    public function index(Request $request, Process $process, Excel $excel)
    {
        $excel->create($process->id, function ($excel) use ($process) {
            $excel->sheet("Leads", function ($sheet) use ($process) {
                $sheet->fromArray($process->getDownloadData());
            });
        })->store('csv', storage_path('excel/exports'));

        $file = storage_path('excel/exports') . "/{$process->id}.csv";

        return response()->download($file);
    }
}
