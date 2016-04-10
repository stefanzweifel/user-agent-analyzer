<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateProcessRequest;
use App\Http\Requests\UploadFileRequest;
use App\Jobs\Notifications\SendUploadNotificationMail;
use App\Jobs\ReadCsvFile;
use App\Models\Process;
use Carbon\Carbon;
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
        $data['expires_at'] = Carbon::parse("1 day");
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
        return view('process.show', compact('process'));
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
        $process->update(['start_at' => Carbon::now()]);

        $this->dispatch(new ReadCsvFile($process));

        return redirect()->route('process.show', [$process->id])->withSuccess('File uploaded.');
    }

}
