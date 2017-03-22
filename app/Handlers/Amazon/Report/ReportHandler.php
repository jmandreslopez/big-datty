<?php namespace App\Handlers\Amazon\Report;

use App\Handlers\BaseRequestHandler;
use App\Helpers\Amazon\ReportHelper;
use App\Models\Account\Account;
use App\Services\Report\ReportService;
use App\Traits\QueueTrait;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use Carbon\Carbon;

class ReportHandler extends BaseRequestHandler
{
    use QueueTrait;

    /**
     * Report Type
     *
     * @var string
     */
    protected $reportType;

    /**
     * Start Date
     *
     * @var string
     */
    protected $startDate;

    /**
     * Set Report Type
     *
     * @param $reportType
     * @return $this
     */
    public function setReportType($reportType)
    {
        $this->reportType = $reportType;

        return $this;
    }

    /**
     * Get Report Type
     *
     * @return array
     */
    public function getReportType()
    {
        return (!empty($this->reportType)) ? [$this->reportType] : config('bigdatty.reports.types');
    }

    /**
     * Set Start Date
     *
     * @param string $startDate
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get Start Date
     *
     * @return bool|string
     */
    public function getStartDate()
    {
        $startDate = (!empty($this->startDate)) ? Carbon::parse($this->startDate) : Carbon::today()->subMonth();

        return $startDate->toIso8601String();
    }

    /**
     * Initialize Request
     */
    public function init()
    {
        $this->options = ReportHelper::API_PARAMETERS;

        // Report Type
        if (! empty($this->arguments['type'])) {
            $this->setReportType($this->arguments['type']);
        }

        // Account Id
        if (! empty($this->arguments['account'])) {
            $this->setAccountId($this->arguments['account']);
        }
    }

    /**
     * Load Requests into the queue
     */
    public function load()
    {
        $query = Account::whereIsActive(true);

        // Account Id
        if (!empty($this->getAccountId())) {
            $query->where('accounts.id', '=', $this->getAccountId());
        }

        debug('Account Count: ' . $query->count());

        $query->chunk(1000, function ($accounts) {
            foreach ($accounts as $account) {

//                // Get stored report requests
//                $storedReportRequests = $account->reports->pluck('type');
//
//                dd($storedReportRequests);
//
//                $endDates = array_column($storedReportRequests, 'end_date');
//                $storedReportRequests = array_combine($reportTypes, $endDates);
//
//                $currentReports = $account->reports()
//                    ->select('type', 'start_date')
//                    ->get();
//
//                $reports = [];
//                foreach ($currentReports as $currentReport) {
//                    $reports[$currentReport->type] = $currentReport->start_date;
//                }
//
//                $reportTypes = $this->getReportType();
//
//                foreach ($reportTypes as $reportType) {
//
//                    $reportTypeExistsInAccount = in_array($reportType, array_keys($reports));
//
//                    if ($reportTypeExistsInAccount) {
//                        $startDate = Carbon::parse($reports[$reportType])->toIso8601String();
//                    }
//                    else {
//                        $startDate = $this->getStartDate();
//                    }
//
//                    $this->options['ReportType'] = $reportType;
//                    $this->options['StartDate'] = $startDate;
//
//                    $this->requestRequestReport($account, $this->options);
//                }

                $reportTypes = $this->getReportType();

                foreach ($reportTypes as $reportType) {
                    $this->options['ReportType'] = $reportType;
                    $this->requestRequestReport($account, $this->options);
                }
            }
        });
    }

    /**
     * Request completed handler
     *
     * @param CompleteEvent $event
     * @param Account $account
     * @param array $options
     */
    protected function handleComplete($event, $account, $options)
    {
        $response = $this->parse($event);

        $report = ReportService::create($account, $response['RequestReportResult']['ReportRequestInfo']);

        $this->queueReport($account, $report, $options);
    }

    /**
     * Request error handler
     *
     * @param ErrorEvent $event
     * @param Account $account
     * @param array $options
     */
    protected function handleError($event, $account, $options)
    {
        debug('Request failed!', 'error');
        debug($event->getException());
    }
}