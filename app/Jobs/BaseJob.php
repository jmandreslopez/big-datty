<?php namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Account\Account;

abstract class BaseJob implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Job attributes
     *
     * @var array
     */
    protected $attributes;

    /**
     * @var Account;
     */
    protected $account;

    /**
     * @param array $attributes
     */
    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Start Job
     */
    public function handle()
    {
        // Account
        $this->account = $this->attributes['account'];

        // Initialize Job
        $this->init();

        // Process Job
        $this->process();

        // Delete job from the queue
        $this->delete();
    }

    /**
     * Initialize Job
     */
    abstract protected function init();

    /**
     * Process Job
     */
    abstract protected function process();
}
