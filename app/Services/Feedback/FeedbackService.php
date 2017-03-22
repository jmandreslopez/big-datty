<?php namespace App\Services\Feedback;

use App\Helpers\Amazon\FeedbackHelper;
use App\Models\Account\Account;
use App\Models\Feedback\Feedback;
use App\Models\Feedback\FeedbackMetadata;
use App\Models\Order\Order;
use App\Services\Account\AccountService;
use Carbon\Carbon;

class FeedbackService
{
    /**
     * Create Feedback
     *
     * @param Account $account
     * @param array $item
     * @return Feedback|bool
     */
    public static function create($account, $item)
    {
        // Order
        $order = (array_key_exists('Order ID', $item)) ?
            $order = Order::whereSourceId($item['Order ID'])->first() :
            null;

        if (self::count($account, $order, $item['Comments']) == 0) {

            // OrderId
            $orderId = (! empty($order)) ? $order->id : null;

            // Date
            $date = self::date($account, $item['Date']);

            // Rating
            $rating = $item['Rating'];

            // IsActive
            $isActive = (!empty($order)) ? true : false;

            $feedback = new Feedback([
                'account_id' => $account->id,
                'order_id'   => $orderId,
                'date'       => $date,
                'rating'     => $rating,
                'is_active'  => $isActive,
            ]);

            // Save Feedback
            $feedback->save();

            // Create Metadata
            self::metadata($feedback, $item);

            debug('Feedback Created: ' . $feedback->id);

            return $feedback;
        }

        return false;
    }

    /**
     * Get count of Feedbacks in the DB
     *
     * @param Account $account
     * @param Order $order
     * @param string $comments
     * @return mixed
     */
    protected static function count($account, $order, $comments)
    {
        $query = Feedback::select('feedbacks.id')
            ->join('feedbacks_metadata', 'feedbacks.id', '=', 'feedbacks_metadata.feedback_id')
            ->where('feedbacks.account_id', '=', $account->id);

        if (! empty($order)) {
            $query->where(function ($query) use ($order) {
                $query->where('meta_key', '=', 'order_id')
                    ->where('meta_value', '=', $order->id);
            });
        }

        if (! empty($comments)) {
            $query->orWhere(function ($query) use ($comments) {
                $query->where('meta_key', '=', 'comments')
                    ->where('meta_value', '=', $comments);
            });
        }

        return $query->groupBy('feedbacks.id')
            ->count();
    }

    /**
     * Update Feedback with OrderId
     *
     * @param int $id
     * @param Order $order
     */
    public static function update($id, $order)
    {
        $feedback = Feedback::find($id);
        $feedback->order_id = $order->id;
        $feedback->is_active = true;
        $feedback->save();

        return $feedback;
    }

    /**
     * Get date based on the format
     *
     * @param Account $account
     * @param string $date
     * @return string
     */
    protected static function date($account, $date)
    {
        // RegEx to find the year format
        $pattern = '/^\d{1,2}\/\d{1,2}\/\d{2}$/';
        if (preg_match($pattern, $date)) {
            $format = '/y';
        }
        else {
            $format = '/Y';
        }

        $country = AccountService::country($account);
        if ($country == 'CA' || $country == 'US') {
            $format = 'm/d' . $format;
        }
        else {
            $format = 'd/m' . $format;
        }

        return Carbon::createFromFormat($format, $date)->toDateTimeString();
    }

    /**
     * Create Metadata
     *
     * @param Feedback $feedback
     * @param array $item
     */
    protected static function metadata($feedback, $item)
    {
        $data = [];
        $metadata = $feedback->metadata->keyBy('meta_key')->all();
        foreach ($item as $key => $value) {
            if (! in_array($key, FeedbackHelper::BLACKLISTED_FIELDS)) {
                $key = meta_key($key);
                $value = meta_value($value);

                if (array_key_exists($key, $metadata)) {
                    $object = $metadata[$key];
                    if ($object->meta_value != $value) {
                        $object->meta_value = $value;
                        $object->save();
                    }
                }
                else {
                    $data[] = [
                        'feedback_id' => $feedback->id,
                        'meta_key'    => $key,
                        'meta_value'  => $value,
                    ];
                }
            }
        }

        // Save FeedbackMetadata
        if (! empty($data)) {
            FeedbackMetadata::insert($data);
        }
    }
}