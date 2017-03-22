<?php namespace App\Console\Commands\Tor;

use Illuminate\Console\Command;
use App\Helpers\CrawlerRequest;

class TorRenewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'tor:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function fire()
    {
        CrawlerRequest::newTorIdentity();
    }
}