<?php namespace App\Helpers\Amazon\Scrapers;

use App\Helpers\Amazon\ScraperHelper;

class InScraper extends ScraperHelper
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
     | on {day} {month} {year}
     |
     */

    const REVIEW_DATE_REGEX = '/on (?P<day>\d{1,2}) (?P<month>[a-zA-Z]+) (?P<year>\d{4})/si';

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
     | asked by {author} on {day} {month}[.] {year}
     |
     */

    const QUESTION_DATE_REGEX = '/asked by (?P<author>.*) on (?P<day>\d{1,2}) (?P<month>[a-zA-Z]+)\.? (?P<year>\d{4})/si';

    /*
     |--------------------------------------------------------------------------
     | ANSWER TOTAL REGEX PATTERN
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
     | {author} answered by {day} {month}[.] {year}
     |
     */

    const ANSWER_DATE_REGEX = '/(?P<author>.*) answered on (?P<day>\d{1,2}) (?P<month>[a-zA-Z]+)\.? (?P<year>\d{4})/si';
}