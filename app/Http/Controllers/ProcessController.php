<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateProcessRequest;
use App\Http\Requests\UploadFileRequest;
use App\Jobs\SendUploadNotificationMail;
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
    public function upload(Request $request, Process $process)
    {
        if ($process->isExpired()) {
            return [
                'message' => 'Process expired.'
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

        $this->dispatch(new \App\Jobs\ReadCsvFile($process));

        return redirect()->route('route.messages.success', [$process->id]);
    }

    /**
     * Display Success Message
     * @param  Request $request
     * @param  Process $process
     * @return View
     */
    public function success(Request $request, Process $process)
    {
        return view('upload-success', compact('process'));
    }

}
