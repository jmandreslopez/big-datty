<?php namespace App\Helpers\Amazon\Scrapers;

use App\Helpers\Amazon\ScraperHelper;

class MxScraper extends ScraperHelper
{
    /*
     |--------------------------------------------------------------------------
     | MONTHS
     |--------------------------------------------------------------------------
     */

    const MONTHS = [
        'enero'      => 'January',
        'ene'        => 'January',
        'febrero'    => 'February',
        'feb'        => 'February',
        'marzo'      => 'March',
        'mar'        => 'March',
        'abril'      => 'April',
        'abr'        => 'April',
        'mayo'       => 'May',
        'may'        => 'May',
        'junio'      => 'June',
        'jun'        => 'June',
        'julio'      => 'July',
        'jul'        => 'July',
        'agosto'     => 'August',
        'ago'        => 'August',
        'septiembre' => 'September',
        'sep'        => 'September',
        'octubre'    => 'October',
        'oct'        => 'October',
        'noviembre'  => 'November',
        'nov'        => 'November',
        'diciembre'  => 'December',
        'dic'        => 'December',
    ];

    /*
     |--------------------------------------------------------------------------
     | REVIEW DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | el {day} de {month} de {year}
     |
     */

    const REVIEW_DATE_REGEX = '/el (?P<day>\d{1,2}) de (?P<month>[a-zA-Z]+) de (?P<year>\d{4})/si';

    /*
     |--------------------------------------------------------------------------
     | QUESTION TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | N/A
     |
     */

    const QUESTION_TOTAL_REGEX = null;

    /*
     |--------------------------------------------------------------------------
     | QUESTION DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | N/A
     |
     */

    const QUESTION_DATE_REGEX = null;

    /*
     |--------------------------------------------------------------------------
     | ANSWER TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | N/A
     |
     */

    const ANSWER_TOTAL_REGEX = null;

    /*
     |--------------------------------------------------------------------------
     | ANSWER DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | N/A
     |
     */

    const ANSWER_DATE_REGEX = null;
}