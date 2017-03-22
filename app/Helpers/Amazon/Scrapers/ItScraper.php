<?php namespace App\Helpers\Amazon\Scrapers;

use App\Helpers\Amazon\ScraperHelper;

class ItScraper extends ScraperHelper
{
    /*
     |--------------------------------------------------------------------------
     | MONTHS
     |--------------------------------------------------------------------------
     */

    const MONTHS = [
        'gennaio'   => 'January',
        'genn'      => 'January',
        'febbraio'  => 'February',
        'febbr'     => 'February',
        'marzo'     => 'March',
        'mar'       => 'March',
        'aprile'    => 'April',
        'apr'       => 'April',
        'maggio'    => 'May',
        'magg'      => 'May',
        'giugno'    => 'June',
        'luglio'    => 'July',
        'agosto'    => 'August',
        'ag'        => 'August',
        'settembre' => 'September',
        'sett'      => 'September',
        'ottobre'   => 'October',
        'ott'       => 'October',
        'novembre'  => 'November',
        'nov'       => 'November',
        'dicembre'  => 'December',
        'dic'       => 'December',
    ];

    /*
     |--------------------------------------------------------------------------
     | REVIEW DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | il {day} {month} {year}
     |
     */

    const REVIEW_DATE_REGEX = '/il (?P<day>\d{1,2}) (?P<month>[a-zA-Z]+) (?P<year>\d{4})/si';

    /*
     |--------------------------------------------------------------------------
     | QUESTION TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | Visualizzazione di {number}-{number} di {total} domande
     |
     */

    const QUESTION_TOTAL_REGEX = '/Visualizzazione di \d+-\d+ di (?P<total>\d+) domande/Uis';

    /*
     |--------------------------------------------------------------------------
     | QUESTION DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | chiesto da {author} il {day} {month}[.] {year}
     |
     */

    const QUESTION_DATE_REGEX = '/chiesto da (?P<author>.*) il (?P<day>\d{1,2}) (?P<month>[a-zA-Z]+)\.? (?P<year>\d{4})/si';

    /*
     |--------------------------------------------------------------------------
     | ANSWER TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | Showing {number}-{number} of {total} risposte
     |
     */

    const ANSWER_TOTAL_REGEX = '/Visualizzazione di \d+-\d+ di (?P<total>\d+) risposte/Uis';

    /*
     |--------------------------------------------------------------------------
     | ANSWER DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {author} risposta fornita il {day} {month}[.] {year}
     |
     */

    const ANSWER_DATE_REGEX = '/(?P<author>.*) risposta fornita il (?P<day>\d{1,2}) (?P<month>[a-zA-Z]+)\.? (?P<year>\d{4})/si';
}