<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateProcessRequest;
use App\Http\Requests\UploadFileRequest;
use App\Jobs\SendUploadNotificationMail;
use App\Jobs\ReadCsvFile;
use App\Models\Process;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    /**
     * Create a new "Process"
     * @param  CreateProcessRequest $request
     * @param  Process              $processModel
     * @return return View
     */
    public function store(CreateProcessRequest $request, Process $processModel)
    {
        $data = $request->all();
        // TODO: Move ExpiresAt Setter to Model
        $data['expires_at'] = \Carbon\Carbon::parse("1 day");
        $process = $processModel->create($data);

        $this->dispatch(new SendUploadNotificationMail($process));

        return redirect()->route('home');
    }

    /**
     * Display View to Upload CSV File
     * @param  Request $request
     * @param  Process $process
     * @return View
     */
    public function show(Request $request, Process $process)
    {
        // TODO: Move this Logic into Middlewares
        if ($process->isFinished()) {

            // We should display data here
            $report = $process->getReport();

            return [
                'message' => 'Process already done.'
            ];
        }

        if ($process->isProcessing()) {
            return [
                'message' => 'Files are being processed.'
            ];
        }

        if ($process->isExpired()) {
            return [
                'message' => 'Process expired.'
            ];
        }

        if ($process->hasReceivedFile())
        {
            return [
                'message' => 'We already got a file for this process.'
            ];
        }

        return view('upload-file', compact('process'));
    }

    /**
     * Upload File and associate it with Process
     * Start Process to Read given File
     * @param  UploadFileRequest $request
     * @param  Process           $process
     * @return Redirect
     */
    public function update(UploadFileRequest $request, Process $process)
    {
        $process->addMedia($request->file('file'))->toCollection('csv-files');

        $this->dispatch(new ReadCsvFile($process));

        return redirect()->route('process.show', [$process->id]);
    }

}
