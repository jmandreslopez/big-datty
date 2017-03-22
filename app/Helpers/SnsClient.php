<?php namespace App\Helpers;

class SnsClient
{
    /**
     * Publish SNS message
     *
     * @param string $arn
     * @param array $message
     */
    public static function publish($arn, $message)
    {
        $sns = app('aws')->createClient('sns');

        $sns->publish([
            'TopicArn' => $arn,
            'Message'  => json_encode($message),
        ]);
    }

    /**
     * Publish SNS message to the Accounts topic
     *
     * @param string $action
     * @param int $account
     * @param string $status
     */
    public static function account($action, $account, $status = null)
    {
        $message = [
            'action'  => $action,
            'account' => $account,
        ];

        if (! empty($status)) {
            $message['status'] = $status;
        }

        self::publish(config('bigdatty.amazon.sns.topics.accounts'), $message);
    }

    /**
     * Publish SNS message to the Orders topic
     *
     * @param string $action
     * @param int $order
     */
    public static function order($action, $order)
    {
        $message = [
            'action' => $action,
            'order'  => $order,
        ];

        self::publish(config('bigdatty.amazon.sns.topics.orders'), $message);
    }

    /**
     * Publish SNS message to the Products topic
     *
     * @param string $action
     * @param int $product
     */
    public static function product($action, $product)
    {
        $message = [
            'action'  => $action,
            'product' => $product,
        ];

        self::publish(config('bigdatty.amazon.sns.topics.products'), $message);
    }

    /**
     * Publish SNS message to the ProductReviews topic
     *
     * @param string $action
     * @param int $review
     */
    public static function review($action, $review)
    {
        $message = [
            'action' => $action,
            'review' => $review,
        ];

        self::publish(config('bigdatty.amazon.sns.topics.reviews'), $message);
    }

    /**
     * Publish SNS message to the ProductQuestions topic
     *
     * @param string $action
     * @param int $question
     */
    public static function question($action, $question)
    {
        $message = [
            'action'   => $action,
            'question' => $question,
        ];

        self::publish(config('bigdatty.amazon.sns.topics.questions'), $message);
    }

    /**
     * Publish SNS message to the ProductHijacks topic
     *
     * @param string $action
     * @param int $hijack
     */
    public static function hijack($action, $hijack)
    {
        $message = [
            'action' => $action,
            'hijack' => $hijack,
        ];

        self::publish(config('bigdatty.amazon.sns.topics.hijacks'), $message);
    }

    /**
     * Publish SNS message to the Feedbacks topic
     *
     * @param string $action
     * @param int $feedback
     */
    public static function feedback($action, $feedback)
    {
        $message = [
            'action'   => $action,
            'feedback' => $feedback,
        ];

        self::publish(config('bigdatty.amazon.sns.topics.feedbacks'), $message);
    }
}