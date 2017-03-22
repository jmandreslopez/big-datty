<?php namespace App\Helpers\Amazon\Scrapers;

use App\Helpers\Amazon\ScraperHelper;

class UsScraper extends ScraperHelper
{
    /*
     |--------------------------------------------------------------------------
     | MONTHS
     |--------------------------------------------------------------------------
     */

    const MONTHS = [
        'Jan' => 'January',
        'Feb' => 'February',
        'Mar' => 'March',
        'Apr' => 'April',
        'May' => 'May',
        'Jun' => 'June',
        'Jul' => 'July',
        'Aug' => 'August',
        'Sep' => 'September',
        'Oct' => 'October',
        'Nov' => 'November',
        'Dec' => 'December',
    ];

    /*
     |--------------------------------------------------------------------------
     | REVIEW DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | on {month} {day}, {year}
     |
     */

    const REVIEW_DATE_REGEX = '/on (?P<month>[a-zA-Z]+) (?P<day>\d{1,2}), (?P<year>\d{4})/si';

    /*
     |--------------------------------------------------------------------------
     | QUESTION TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | Showing {number}-{number} of {total} questions
     |
     */

    const QUESTION_TOTAL_REGEX = '/Showing \d+-\d+ of (?P<total>\d+) questions/Uis';

    /*
     |--------------------------------------------------------------------------
     | QUESTION DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | asked by {author} on {month} {day}, {year}
     |
     */

    const QUESTION_DATE_REGEX = '/asked by (?P<author>.*) on (?P<month>[a-zA-Z]+) (?P<day>\d{1,2}), (?P<year>\d{4})/si';

    /*
     |--------------------------------------------------------------------------
     | ANSWER DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | Showing {number}-{number} of {total} answers
     |
     */

    const ANSWER_TOTAL_REGEX = '/Showing \d+-\d+ of (?P<total>\d+) answers/Uis';

    /*
     |--------------------------------------------------------------------------
     | ANSWER DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {author} answered by {month} {day}, {year}
     |
     */

    const ANSWER_DATE_REGEX = '/(?P<author>.*) answered on (?P<month>[a-zA-Z]+) (?P<day>\d{1,2}), (?P<year>\d{4})/si';
}



