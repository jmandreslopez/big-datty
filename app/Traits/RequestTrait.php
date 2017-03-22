<?php namespace App\Traits;

use App\Models\Account\Account;
use App\Models\Order\Order;
use App\Models\Report\Report;
use GuzzleHttp\Message\Request;
use Carbon\Carbon;

trait RequestTrait
{
    use ParametersTrait, QueueTrait;

    /**
     * Request NextToken
     *
     * @param Account $account
     * @param string $action
     * @param string $nextToken
     * @param array $options
     * @param bool $return
     * @return Request
     */
    protected function requestNextToken($account, $action, $nextToken, $options, $return = false)
    {
        // Parameters
        $parameters = $this->getParameters($account, $action);
        $parameters['NextToken'] = (string)$nextToken;

        // Options
        $options['action'] = $action;

        if ($return) {
            return $this->getRequest($parameters, $options);
        }

        $this->addRequest($account, $parameters, $options);
    }

    /*
    |--------------------------------------------------------------------------
    | HANDLER REQUESTS
    |--------------------------------------------------------------------------
    */

    /**
     * Request ListMarketplaceParticipations
     *
     * @param Account $account
     * @param array $options
     */
    protected function requestListMarketplaceParticipations($account, $options)
    {
        // Action
        $action = 'ListMarketplaceParticipations';

        // Parameters
        $parameters = $this->getParameters($account, $action);

        // Options
        $options['action'] = $action;

        $this->addRequest($account, $parameters, $options);
    }

    /**
     * Request ListOrders
     *
     * @param Account $account
     * @param array $options
     */
    protected function requestListOrders($account, $options)
    {
        $metadata = $account->marketplace->details();

        // Action
        $action = 'ListOrders';

        // Parameters
        $parameters = $this->getParameters($account, $action);
        $parameters['MarketplaceId.Id.1'] = $metadata['MarketplaceId'];
        $parameters['LastUpdatedAfter'] = Carbon::now()->tz('utc')->subHour(config('bigdatty.orders.length'))->toIso8601String();

        // Options
        $options['action'] = $action;

        $this->addRequest($account, $parameters, $options);
    }

    /**
     * Request ListOrderItems
     *
     * @param Account $account
     * @param Order $order
     * @param array $options
     */
    protected function requestListOrderItems($account, $order, $options)
    {
        // Action
        $action = 'ListOrderItems';

        // Parameters
        $parameters = $this->getParameters($account, $action);
        $parameters['AmazonOrderId'] = $order->source_id;

        // Options
        $options['action'] = $action;
        $options['order'] = $order;

        $this->addRequest($account, $parameters, $options);
    }

    /**
     * Request RequestReport
     *
     * @param Account $account
     * @param array $options
     */
    protected function requestRequestReport($account, $options)
    {
        // Action
        $action = 'RequestReport';

        // Parameters
        $parameters = $this->getParameters($account, $action);
        $parameters['ReportType'] = $options['ReportType'];
        $parameters['StartDate'] = Carbon::now()->tz('utc')->subDays(config('bigdatty.reports.length'))->toIso8601String();
        $parameters['EndDate']   = Carbon::now()->tz('utc')->toIso8601String();

        // Options
        $options['action'] = $action;

        $this->addRequest($account, $parameters, $options);
    }

    /*
    |--------------------------------------------------------------------------
    | JOB REQUESTS
    |--------------------------------------------------------------------------
    */

    /**
     * Request GetOrder
     *
     * @param Account $account
     * @param Order|string $order
     * @param array $options
     * @return Request
     */
    protected function requestGetOrder($account, $order, $options)
    {
        // Action
        $action = 'GetOrder';

        // Parameters
        $parameters = $this->getParameters($account, $action);
        if (is_array($order)) {
            foreach ($order as $index => $value) {
                $parameters['AmazonOrderId.Id.'.($index+1)] = $value;
            }
        }
        else {
            $parameters['AmazonOrderId.Id.1'] = $order->source_id;
        }

        // Options
        $options['action'] = $action;
        $options['order'] = $order;

        return $this->getRequest($parameters, $options);
    }

    /**
     * Request GetReportRequestList
     *
     * @param Account $account
     * @param Report $report
     * @param array $options
     * @return Request
     */
    protected function requestGetReportRequestList($account, $report, $options)
    {
        // Action
        $action = 'GetReportRequestList';

        // Parameters
        $parameters = $this->getParameters($account, $action);
        $parameters['ReportRequestIdList.Id.1'] = $report->report_request_id;

        // Options
        $options['action'] = $action;

        return $this->getRequest($parameters, $options);
    }

    /**
     * Request GetReportList
     *
     * @param Account $account
     * @param array $report
     * @param array $options
     * @return Request
     */
    protected function requestGetReportList($account, $report, $options)
    {
        // Action
        $action = 'GetReportList';

        // Parameters
        $parameters = $this->getParameters($account, $action);
        $parameters['ReportRequestIdList.Id.1'] = $report['ReportRequestId'];
        $parameters['ReportTypeList.Type.1'] = $report['ReportType'];

        // Options
        $options['action'] = $action;

        return $this->getRequest($parameters, $options);
    }

    /**
     * Request GetReport
     *
     * @param Account $account
     * @param array $report
     * @param array $options
     * @return Request
     */
    protected function requestGetReport($account, $report, $options)
    {
        // Action
        $action = 'GetReport';

        // Parameters
        $parameters = $this->getParameters($account, $action);
        $parameters['ReportId'] = (array_key_exists('ReportId', $report)) ? $report['ReportId'] : $report['GeneratedReportId'];

        // Options
        $options['action'] = $action;
        $options['ReportRequestId'] = $report['ReportRequestId'];
        $options['ReportType'] = $report['ReportType'];
        $options['GeneratedReportId'] = $report['GeneratedReportId'];

        return $this->getRequest($parameters, $options);
    }

    /**
     * Request UpdateReportAcknowledgements
     *
     * @param Account $account
     * @param string $report
     * @param bool $result
     * @param array $options
     * @return Request
     */
    protected function requestUpdateReportAcknowledgements($account, $report, $result, $options)
    {
        // Action
        $action = 'UpdateReportAcknowledgements';

        // Parameters
        $parameters = $this->getParameters($account, $action);
        $parameters['ReportIdList.Id.1'] = $report;
        $parameters['Acknowledged'] = $result;

        // Options
        $options['action'] = $action;

        return $this->getRequest($parameters, $options);
    }
}