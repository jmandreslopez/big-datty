<?php namespace App\Helpers\Amazon;

use App\Helpers\Amazon\Scrapers\CaScraper;
use App\Helpers\Amazon\Scrapers\CnScraper;
use App\Helpers\Amazon\Scrapers\DeScraper;
use App\Helpers\Amazon\Scrapers\EsScraper;
use App\Helpers\Amazon\Scrapers\FrScraper;
use App\Helpers\Amazon\Scrapers\InScraper;
use App\Helpers\Amazon\Scrapers\ItScraper;
use App\Helpers\Amazon\Scrapers\JpScraper;
use App\Helpers\Amazon\Scrapers\MxScraper;
use App\Helpers\Amazon\Scrapers\UkScraper;
use App\Helpers\Amazon\Scrapers\UsScraper;
use Carbon\Carbon;

class ScraperHelper
{
    /*
     |--------------------------------------------------------------------------
     | DATE FORMAT
     |--------------------------------------------------------------------------
     |
     | {Month} {day} {year}
     |
     */

    const DATE_FORMAT = 'F j, Y';

    /*
     |--------------------------------------------------------------------------
     | QUESTION ID REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | http://{domain}/forum/-/{id}/
     |
     */

    const QUESTION_ID_REGEX = '/forum\/-\/(?P<id>.*)\//Uis';

    /*
     |--------------------------------------------------------------------------
     | HIJACK SELLER ID REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | http://{domain}/shops/{id}/
     |
     */

    const HIJACK_SELLER_ID_REGEX = '/shops\/(?P<id>.*)\//Uis';


    /**
     * @param $country
     * @return CaScraper|CnScraper|DeScraper|JpScraper|MxScraper|UkScraper|UsScraper|bool
     */
    public static function getScraper($country)
    {
        switch ($country)
        {
            case 'CA':
                return new CaScraper();

            case 'US':
                return new UsScraper();

            case 'DE':
                return new DeScraper();

            case 'ES':
                return new EsScraper();

            case 'FR':
                return new FrScraper();

            case 'IN':
                return new InScraper();

            case 'IT':
                return new ItScraper();

            case 'UK':
                return new UkScraper();

            case 'JP':
                return new JpScraper();

            case 'CN':
                return new CnScraper();

            case 'MX':
                return new MxScraper();

            default:
                return false;
        }
    }

    /**
     * Convert month to English
     *
     * @param string $date
     * @param ScraperHelper $scraper
     * @return Carbon
     */
    public function getDate($date, $scraper)
    {
        if (! empty($scraper::MONTHS)) {
            if (array_key_exists($date['month'], $scraper::MONTHS)) {
                $date['month'] = $scraper::MONTHS[$date['month']];
            }
        }

        $time = $date['month'].' '.$date['day'].', '.$date['year'];

        return Carbon::createFromFormat(self::DATE_FORMAT, $time);
    }
}