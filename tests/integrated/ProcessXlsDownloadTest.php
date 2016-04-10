<?php

use App\Models\Process;
use App\Models\UserAgent;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ProcessXlsDownloadTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_downloads_xls_file_for_given_process()
    {
        $process    = factory(Process::class)->create();
        $userAgents = factory(UserAgent::class, 5)->create(['process_id' => $process->id]);

        $response = $this->call('GET', "resource/{$process->id}/downloads/xls");

        $file = storage_path('excel/exports') . "/{$process->id}.xls";

        $fileContent = File::get($file);

        $this->assertFileExists($file);
        $this->assertContains($process->id, $fileContent);

        File::delete($file);
    }
}
