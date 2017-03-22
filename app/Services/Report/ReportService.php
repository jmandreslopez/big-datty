<?php namespace App\Services\Report;

use App\Models\Account\Account;
use App\Models\Report\Report;
use App\Helpers\Amazon\ReportHelper;
use Exception;

class ReportService
{
    /**
     * Create Report
     *
     * @param Account $account
     * @param array $item
     * @return Report
     */
    public static function create($account, $item)
    {
        // Create Report
        $report = new Report([
            'account_id'        => $account->id,
            'report_request_id' => $item['ReportRequestId'],
            'type'              => $item['ReportType'],
            'submitted_at'      => $item['SubmittedDate'],
            'start_date'        => $item['StartDate'],
            'end_date'          => $item['EndDate'],
            'status'            => $item['ReportProcessingStatus'],
        ]);

        // Save Report
        $report->save();

        debug('Report Created: ' . $report->id);

        return $report;
    }

    /**
     * Update Report status
     *
     * @param array $report
     */
    public static function updateStatus($report)
    {
        Report::whereReportRequestId($report['ReportRequestId'])
            ->update([
                'status' => $report['ReportProcessingStatus']
            ]);
    }

    /**
     * Acknowledged Report
     *
     * @param array $report
     */
    public static function acknowledged($report)
    {
        if ($report['Acknowledged'] == 'true') {
            Report::whereReportRequestId($report['ReportRequestId'])
                ->update([
                    'status' => ReportHelper::STATUS_ACKNOWLEDGED
                ]);
        }
    }

    /**
     * Get Report class
     *
     * @param string $reportType
     * @return string
     * @throws Exception
     */
    public static function getClass($reportType)
    {
        // Class folder
        $folder = self::folder($reportType, ReportHelper::TYPES);

        if (!$folder) {
            throw new Exception('Report type handler not found');
        }

        $file = '';
        $elements = explode("_", $reportType);
        foreach ($elements as $element) {
            if (!empty($element)) {
                $file .= ucfirst(strtolower($element));
            }
        }
        $file = ucfirst($file);

        return '\App\Reports\Amazon\\'.$folder.$file;
    }

    /**
     * Get Report location
     *
     * @param string $needle
     * @param array $reports
     * @return bool|string
     */
    protected static function folder($needle, $reports)
    {
        foreach ($reports as $index => $report) {
            foreach ($report as $indexType => $type) {
                if (is_array($type)) {
                    foreach ($type as $subtype) {
                        if ($needle == $subtype) {
                            return $index . '\\' . $indexType . '\\';
                        }
                    }
                }
                else {
                    if ($needle == $type) {
                        return $index.'\\';
                    }
                }
            }
        }

        return false;
    }

    /**
     * Returns a processed array from a report in a TSV string
     *
     * @param string $report
     * @return array
     */
    public static function toArray($report)
    {
        // Init
        $headers = [];
        $rows = [];

        $report = preg_replace('/[>][\r\n]/i', '>', $report);
        $lines  = explode("\n", $report);

        foreach ($lines as $key => $line) {
            if (!empty($line)) {
                $values = explode("\t", $line);
                foreach ($values as $index => $value) {
                    if (!empty($value)) {
                        if ($key == 0) {
                            $headers[] = trim($value);
                        }
                        else {
                            $value = trim($value);
                            $rows[$key][$headers[$index]] = (!empty($value)) ?
                                htmlentities($value, ENT_QUOTES, 'utf-8') : null;
                        }
                    }
                }
            }
        }

        return [
            'headers' => $headers,
            'rows'    => $rows
        ];
    }
}