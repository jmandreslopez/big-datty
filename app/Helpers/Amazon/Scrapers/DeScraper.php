<?php namespace App\Helpers\Amazon\Scrapers;

use App\Helpers\Amazon\ScraperHelper;

class DeScraper extends ScraperHelper
{
    /*
     |--------------------------------------------------------------------------
     | MONTHS
     |--------------------------------------------------------------------------
     */

    const MONTHS = [
        'Januar'    => 'January',
        'Jan'       => 'January',
        'Jän'       => 'January',
        'Februar'   => 'February',
        'Feb'       => 'February',
        'März'      => 'March',
        'April'     => 'April',
        'Apr'       => 'April',
        'Mai'       => 'May',
        'Juni'      => 'June',
        'Juli'      => 'July',
        'August'    => 'August',
        'Aug'       => 'August',
        'September' => 'September',
        'Sept'      => 'September',
        'Oktober'   => 'October',
        'Okt'       => 'October',
        'November'  => 'November',
        'Nov'       => 'November',
        'Dezember'  => 'December',
        'Dez'       => 'December',
    ];

    /*
     |--------------------------------------------------------------------------
     | REVIEW DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | am {day}. {month} {year}
     |
     */

    const REVIEW_DATE_REGEX = '/am (?P<day>\d{1,2})\. (?P<month>[a-zA-ZÀ-ÿ]+) (?P<year>\d{4})/si';

    /*
     |--------------------------------------------------------------------------
     | QUESTION TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {number}-{number} von {total} Fragen werden angezeigt
     |
     */

    const QUESTION_TOTAL_REGEX = '/\d+-\d+ von (?P<total>\d+) Fragen werden angezeigt/Uis';

    /*
     |--------------------------------------------------------------------------
     | QUESTION DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | gefragt von {author} am {day}. {month} {year}
     |
     */

    const QUESTION_DATE_REGEX = '/gefragt von (?P<author>.*) am (?P<day>\d{1,2})\. (?P<month>[a-zA-ZÀ-ÿ]+) (?P<year>\d{4})/si';

    /*
     |--------------------------------------------------------------------------
     | ANSWER TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {number}-{number} von {total} Antworten werden angezeigt
     |
     */

    const ANSWER_TOTAL_REGEX = '/\d+-\d+ von (?P<total>\d+) Antworten werden angezeigt/Uis';

    /*
     |--------------------------------------------------------------------------
     | ANSWER DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {author} antwortete am {day}. {month} {year}
     |
     */

    const ANSWER_DATE_REGEX = '/(?P<author>.*) antwortete am (?P<day>\d{1,2})\. (?P<month>[a-zA-ZÀ-ÿ]+) (?P<year>\d{4})/si';
}