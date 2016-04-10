<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class ProcessXlsDownloadsController extends Controller
{
    public function index(Request $request, Process $process, Excel $excel)
    {
        $excel->create($process->id, function ($excel) use ($process) {
            $excel->sheet('Leads', function ($sheet) use ($process) {
                $sheet->fromArray($process->getDownloadData());
            });
        })->store('xls', storage_path('excel/exports'));

        $file = storage_path('excel/exports')."/{$process->id}.xls";

        return response()->download($file);
    }
}
