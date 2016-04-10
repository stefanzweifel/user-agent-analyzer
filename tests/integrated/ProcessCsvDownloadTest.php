<?php

use App\Models\Process;
use App\Models\UserAgent;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProcessCsvDownloadTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_downloads_csv_file_for_given_process()
    {
        $process = factory(Process::class)->create();
        $userAgents = factory(UserAgent::class, 5)->create(['process_id' => $process->id]);

        $response = $this->call('GET', "resource/{$process->id}/downloads/csv");

        $file = storage_path('excel/exports')."/{$process->id}.csv";

        $fileContent = File::get($file);

        $this->assertFileExists($file);
        $this->assertContains($process->id, $fileContent);

        File::delete($file);
    }
}
