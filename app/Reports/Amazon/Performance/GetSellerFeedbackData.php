<?php namespace App\Reports\Amazon\Performance;

use App\Reports\BaseFlatReport;
use App\Services\Feedback\FeedbackService;
use App\Traits\QueueTrait;
use App\Helpers\SnsClient;

/**
 * _GET_SELLER_FEEDBACK_DATA_
 *
 * Flat File Feedback Report
 */
class GetSellerFeedbackData extends BaseFlatReport
{
    use QueueTrait;

    protected function process()
    {
        $orders = [];
        foreach ($this->report['rows'] as $item) {

            $feedback = FeedbackService::create($this->account, $item);

            if (! empty($feedback)) {
                if (empty($feedback->order_id) && isset($item['Order ID'])) {
                    $orders[$item['Order ID']][] = $feedback->id;
                }
                else {
                    SnsClient::feedback('new', $feedback->id);
                }
            }
        }

        if (! empty($orders)) {
            $this->queueFeedback($this->account, $orders, $this->options);
        }
    }
}