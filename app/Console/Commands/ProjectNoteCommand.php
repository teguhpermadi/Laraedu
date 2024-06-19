<?php

namespace App\Console\Commands;

use App\Jobs\ProjectNoteJob;
use Illuminate\Console\Command;

class ProjectNoteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:project-note-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert project note';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ProjectNoteJob::dispatch();
        $this->info('The command was successful!');
    }
}
