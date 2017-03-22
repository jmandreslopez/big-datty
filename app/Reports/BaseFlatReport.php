<?php namespace App\Reports;

use App\Services\Report\ReportService;
use GuzzleHttp\Message\ResponseInterface;

abstract class BaseFlatReport extends BaseReport
{
    /**
     * @param ResponseInterface $response
     * @param array $options
     * @return bool
     */
    public function init($response, $options)
    {
        $this->report = ReportService::toArray($response->getBody());

        $this->options = $options;

        $this->process();

        return true;
    }

    /**
     * Process Report
     *
     * @return mixed
     */
    abstract protected function process();
}