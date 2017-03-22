<?php namespace App\Services\Customer;

use App\Models\Customer\Customer;

class CustomerService
{
    /**
     * Create Customer
     *
     * @param $item
     * @return Customer|bool
     */
    public static function create($item)
    {
        if (array_key_exists('BuyerEmail', $item) && array_key_exists('BuyerName', $item)) {

            $customer = Customer::whereEmail($item['BuyerEmail'])->first();
            if (empty($customer)) {

                $customer = new Customer([
                    'name'  => $item['BuyerName'],
                    'email' => $item['BuyerEmail'],
                ]);

                // Save Customer
                $customer->save();

                debug('Customer Created: ' . $customer->id);
            }

            // Update Name
            self::updateName($customer, $item['BuyerName']);

            return $customer;
        }

        return false;
    }

    /**
     * Update Customer name if empty
     *
     * @param Customer $customer
     * @param string $name
     */
    protected static function updateName($customer, $name)
    {
        if (empty($customer->name)) {

            // Update name
            $customer->name = $name;

            // Save Customer
            $customer->save();

            debug('Customer Name Updated: ' . $customer->id);
        }
    }
}