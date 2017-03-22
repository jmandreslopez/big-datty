<?php

if (! function_exists('exists'))
{
    /**
     * Check if a key exists in the array, otherwise return the default
     *
     * @param string $key
     * @param array $array
     * @param null $default
     * @return null
     */
    function exists($key, $array, $default = null)
    {
        return (array_key_exists($key, $array)) ? $array[$key] : $default;
    }
}

if (! function_exists('decimals'))
{
    /**
     * @param $value
     * @return string
     */
    function decimals($value)
    {
        // Amount of decimals to use
        $decimals = 2;

        return number_format($value, $decimals);
    }
}

if (! function_exists('amount'))
{
    /**
     * @param array $array
     * @param null $default
     * @return string
     */
    function amount($array, $default = null)
    {
        if ($array === null) {
            return $default;
        }

        return (array_key_exists('Amount', $array)) ? decimals((float)$array['Amount']) : $default;
    }
}

if (! function_exists('meta_key'))
{
    /**
     * Get metadata key
     * Convert CamelCase to snake_case
     *
     * @param $value
     * @return string
     */
    function meta_key($value)
    {
        $value = str_replace('ID', 'Id', $value);
        $value = str_replace('MWS', 'Mws', $value);

        return snake_case($value);
    }
}

if (! function_exists('meta_value'))
{
    /**
     * Get metadata value
     * If object enconde as json
     *
     * @param $value
     * @return string
     */
    function meta_value($value)
    {
        return (is_object($value) || is_array($value)) ? json_encode($value) : $value;
    }
}

if (! function_exists('md5_header'))
{
    /**
     * @param string $header
     * @param string $body
     * @return bool
     */
    function md5_header($header, $body)
    {
        return ($header == (base64_encode(md5($body, true))));
    }
}

if (! function_exists('is_json'))
{
    /**
     * Check if a string is a json object, if so return an array with
     * thw content otherwise return false
     *
     * @param string $string
     * @return array|bool
     */
    function is_json($string)
    {
        $json = json_decode($string, true);

        return is_string($string) && is_array($json) && (json_last_error() == JSON_ERROR_NONE) ? $json : false;
    }
}

if (! function_exists('parse_xml'))
{
    /**
     * Parse XML and output an array
     *
     * @param $payload
     * @return array
     */
    function parse_xml($payload)
    {
        $parser = new \Nathanmac\Utilities\Parser\Parser();

        return $parser->xml($payload);
    }
}

if (! function_exists('debug'))
{
    /**
     * Output when DEBUG is on
     *
     * @param string $message
     * @param string $type
     */
    function debug($message, $type = '')
    {
        if (env('APP_DEBUG', false)) {
            if (empty($type)) {
                echo $message . "\n";
            }
            else {
                $output = new \Symfony\Component\Console\Output\ConsoleOutput();
                switch ($type) {
                    case 'info':
                        $output->writeln("<info>$message</info>");
                        break;
                    case 'comment':
                        $output->writeln("<comment>$message</comment>");
                        break;
                    case 'error':
                        $output->writeln("<error>$message</error>");
                        break;
                    case 'warning':
                        $style = new \Symfony\Component\Console\Formatter\OutputFormatterStyle('yellow');
                        $output->getFormatter()->setStyle('warning', $style);
                        $output->writeln("<warning>$message</warning>");
                        break;
                    default:
                        break;
                }
            }
        }
        else {
            //\Illuminate\Support\Facades\Log::info($message);
        }
    }
}

if (! function_exists('debug_pool'))
{
    /**
     * Debug GuzzlePool requests
     *
     * @param string $action
     * @param \GuzzleHttp\Event\AbstractRetryableEvent $event
     * @param bool $timestamp
     */
    function debug_pool($action, $event, $timestamp = true)
    {
        if (env('APP_DEBUG', false)) {

            // Request Id
            $requestId = md5(spl_object_hash($event->getRequest()));

            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            switch ($action) {
                case 'before':
                    $message = "About to submit: \t" . $requestId;
                    if ($timestamp) {
                        $message .= "\t" . \Carbon\Carbon::now()->toIso8601String();
                    }
                    $output->writeln("<comment>$message</comment>");
                    break;
                case 'complete':
                    $message = "Completed request: \t" . $requestId;
                    if ($timestamp) {
                        $message .= "\t" . \Carbon\Carbon::now()->toIso8601String();
                    }
                    $output->writeln("<info>$message</info>");
                    break;
                case 'error':
                    $message = "Request failed: \t" . $requestId;
                    if ($timestamp) {
                        $message .= "\t" . \Carbon\Carbon::now()->toIso8601String();
                    }
                    $output->writeln("<error>$message</error>");
                    break;
                default:
                    break;
            }
        }
    }
}

if (! function_exists('d'))
{
    /**
     * Dump object, similar to dd() without die
     */
    function d()
    {
        array_map(function ($object) {
            (new \Illuminate\Support\Debug\Dumper())->dump($object);
        }, func_get_args());
    }
}