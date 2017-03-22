<?php namespace App\Jobs\Amazon\Report;

use App\Jobs\BaseRequestJob;
use App\Helpers\Amazon\ReportHelper;
use App\Models\Report\Report;
use App\Services\Report\ReportService;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Message\ResponseInterface;

class ReportJob extends BaseRequestJob
{
    /**
     * @var Report
     */
    protected $report;

    /**
     * Process Job
     */
    protected function process()
    {
        $this->report = $this->attributes['report'];

        $this->requestGetReportRequestList($this->account, $this->report, $this->options);
    }

    /**
     * Request completed handler
     *
     * @param CompleteEvent $event
     */
    protected function handleComplete($event)
    {
        if ($this->options['action'] == 'GetReport') {
            $headers = $event->getResponse()->getHeaders();
            if (! md5_header($headers['Content-MD5'][0], $event->getResponse()->getBody())) {
                debug('Corrupted Report!', 'error');
            }
            else {
                $this->processGetReport($event->getResponse());
            }
        }
        else {
            // Parse Response
            $response = $this->parse($event);

            switch ($this->options['action']) {
                case 'GetReportRequestList':
                    $this->processGetReportRequestList($response['GetReportRequestListResult']);
                    break;

                case 'GetReportList':
                    $this->processGetReportList($response['GetReportListResult']);
                    break;

                case 'UpdateReportAcknowledgements':
                    ReportService::acknowledged($response['UpdateReportAcknowledgementsResult']['ReportInfo']);
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * Process NextToken
     *
     * @param array $response
     * @param string $action
     */
    protected function processNextToken($response, $action)
    {
        if (array_key_exists('HasNext', $response) && $response['HasNext'] == "true") {
            $this->queueNextToken($this->account, $action, $response['NextToken'], $this->options);
        }
    }

    /**
     * Process GetReportRequestList
     *
     * @param array $response
     */
    protected function processGetReportRequestList($response)
    {
        $this->processNextToken($response, 'GetReportRequestListByNextToken');

        $report = $response['ReportRequestInfo'];
        if (array_key_exists('ReportRequestId', $report)) {

            debug('Status: ' . $report['ReportProcessingStatus']);

            if ($report['ReportProcessingStatus'] == ReportHelper::STATUS_DONE) {
                if (array_key_exists('GeneratedReportId', $report)) {
                    $this->requestGetReport($this->account, $report, $this->options);
                }
                else {
                    $this->requestGetReportList($this->account, $report, $this->options);
                }
            }

            ReportService::updateStatus($report);
        }
    }

    /**
     * Process GetReportList
     *
     * @param array $response
     */
    protected function processGetReportList($response)
    {
        $this->processNextToken($response, 'GetReportListByNextToken');

        $report = $response['ReportInfo'];
        if (array_key_exists('ReportId', $report)) {
            $this->requestGetReport($this->account, $report, $this->options);
        }
    }

    /**
     * Process GetReport
     *
     * @param ResponseInterface $response
     */
    protected function processGetReport($response)
    {
        debug('Processing Report for Account: ' . $this->account->id);

        $class = ReportService::getClass($this->options['ReportType']);
        $result = (new $class($this->account))->init($response, $this->options);

        $this->requestUpdateReportAcknowledgements($this->account, $this->options['GeneratedReportId'], $result, $this->options);
    }
}