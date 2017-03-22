<?php namespace App\Traits;

trait DetailsTrait
{
    /**
     * Get details (metadata) in a key-value pair
     *
     * @return array
     */
    public function details()
    {
        $details = [];
        foreach ($this->metadata as $metadata) {
            $key = ucfirst(camel_case($metadata->meta_key));
            $json = is_json($metadata->meta_value);
            $details[$key] = ($json !== false) ? $json : $metadata->meta_value;
        }

        return $details;
    }
}