<?php namespace App\Reports;

use GuzzleHttp\Message\ResponseInterface;

abstract class BaseXmlReport extends BaseReport
{
    protected $report;

    /**
     * @param ResponseInterface $response
     * @param array $options
     * @return bool
     */
    public function init($response, $options)
    {
        $this->report = parse_xml($response->getBody()->getContents());

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