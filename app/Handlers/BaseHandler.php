<?php namespace App\Handlers;

abstract class BaseHandler
{
    /**
     * Command arguments
     *
     * @var array
     */
    protected $arguments;

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function __construct($arguments = [])
    {
        $this->arguments = $arguments;

        $this->init();

        $this->load();
    }

    /**
     * Initialize
     */
    abstract protected function init();

    /**
     * Load the queue
     */
    abstract protected function load();
}