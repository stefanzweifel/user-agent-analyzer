<?php

use App\Models\Process;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UploadFileForProcessTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_shows_upload_form_for_valid_process()
    {
        $process = factory(Process::class)->create();

        $this->visit("resource/{$process->id}/upload")
            ->see('Upload File');
    }

    /** @test */
    public function it_receives_csv_files()
    {
        $this->expectsJobs(App\Jobs\ReadCsvFile::class);

        $process = factory(Process::class)->create();
        $pathToTestFile = base_path('tests/support/ua-test.csv');

        $this->visit("resource/{$process->id}/upload")
            ->attach($pathToTestFile, 'file')
            ->press('Upload and start process');
    }

    /** @test */
    public function it_shows_error_message_if_wrong_file_type_is_uploaded()
    {
        $process = factory(Process::class)->create();
        $pathToTestFile = base_path('tests/support/not-a-csv.jpg');

        $this->visit("resource/{$process->id}/upload")
            ->attach($pathToTestFile, 'file')
            ->press('Upload and start process')
            ->see('The file must be a file of type: csv, txt.');
    }

    /** @test */
    public function it_shows_error_message_if_process_was_not_found()
    {
        $response = $this->call('GET', "resource/not-an-id/upload");
        $this->assertEquals(404, $response->status());

        // TODO
        // $response = $this->visit("resource/not-an-id/upload")
        //     ->see('Sorry, the page you are looking for could not be found.');
    }

    /** @test */
    public function it_shows_error_message_if_process_is_expired()
    {
        $process = factory(Process::class)->create(['expires_at' => Carbon::parse('2 days ago')]);

        $this->visit("resource/{$process->id}/upload")
            ->seeJson(['message' => 'Process expired.']);
    }

    /** @test */
    public function it_shows_error_message_if_we_already_got_a_file_for_this_process()
    {
        $pathToTestFile = base_path('tests/support/ua-test.csv');
        $process        = factory(Process::class)->create();
        $process->addMedia($pathToTestFile)->preservingOriginal()->toCollection('csv-files');

        $response = $this->visit("resource/{$process->id}/upload")
            ->seeJson(['message' => 'We already got a file for this process.']);
    }

    /** @test */
    public function it_shows_message_if_process_is_already_finished()
    {
        $pathToTestFile = base_path('tests/support/ua-test.csv');
        $process        = factory(Process::class)->create(['finished_at' => Carbon::parse("1 minute ago")]);
        $process->addMedia($pathToTestFile)->preservingOriginal()->toCollection('csv-files');

        $this->visit("resource/{$process->id}/upload")
            ->seeJson(['message' => 'Process already done.']);
    }

    /** @test */
    public function it_shows_message_if_we_are_processing_this_process()
    {
        $pathToTestFile = base_path('tests/support/ua-test.csv');
        $process = factory(Process::class)->create(['start_at' => Carbon::parse('1 minute ago')]);
        $process->addMedia($pathToTestFile)->preservingOriginal()->toCollection('csv-files');

        $this->visit("resource/{$process->id}/upload")
            ->seeJson(['message' => "Files are being processed."]);
    }
}
