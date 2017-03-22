<?php namespace App\Console\Commands\Tor;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Helpers\CrawlerRequest;

class TorIpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'tor:ip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Specify the arguments and options on the command.
     *
     * @return void
     */
    protected function specifyParameters()
    {
        $this->addOption('force-renew', null, InputOption::VALUE_NONE, '');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function fire()
    {
        if ($this->option('force-renew')) {
            $this->call('tor:renew');
        }

        debug('IP: ' . CrawlerRequest::getRequest('https://api.ipify.org')['bodyContent']);
    }
}