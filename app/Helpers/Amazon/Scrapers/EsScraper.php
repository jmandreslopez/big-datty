<?php namespace App\Helpers\Amazon\Scrapers;

use App\Helpers\Amazon\ScraperHelper;

class EsScraper extends ScraperHelper
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
     | Mostrando {number}-{number} de {total} respuestas
     |
     */

    const QUESTION_TOTAL_REGEX = '/Mostrando \d+-\d+ de (?P<total>\d+) respuestas/Uis';

    /*
     |--------------------------------------------------------------------------
     | QUESTION DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | preguntado por {author} el {day} {month} {year}
     |
     */

    const QUESTION_DATE_REGEX = '/preguntado por (?P<author>.*) el (?P<day>\d{1,2}) (?P<month>[a-zA-Z]+) (?P<year>\d{4})/si';

    /*
     |--------------------------------------------------------------------------
     | ANSWER TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | Mostrando {number}-{number} de {total} respuestas
     |
     */

    const ANSWER_TOTAL_REGEX = '/Mostrando \d+-\d+ de (?P<total>\d+) respuestas/Uis';

    /*
     |--------------------------------------------------------------------------
     | ANSWER DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {author} respondió el {day} {month} {year}
     |
     */

    const ANSWER_DATE_REGEX = '/(?P<author>.*) respondió el (?P<day>\d{1,2}) (?P<month>[a-zA-ZÀ-ÿ]+) (?P<year>\d{4})/si';
}