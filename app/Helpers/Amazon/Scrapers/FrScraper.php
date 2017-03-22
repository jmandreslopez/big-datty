<?php namespace App\Helpers\Amazon\Scrapers;

use App\Helpers\Amazon\ScraperHelper;

class FrScraper extends ScraperHelper
{
    /*
     |--------------------------------------------------------------------------
     | MONTHS
     |--------------------------------------------------------------------------
     */

    const MONTHS = [
        'janvier'   => 'January',
        'janv'      => 'January',
        'février'   => 'February',
        'févr'      => 'February',
        'mars'      => 'March',
        'avril'     => 'April',
        'mai'       => 'May',
        'juin'      => 'June',
        'juillet'   => 'July',
        'août'      => 'August',
        'septembre' => 'September',
        'sept'      => 'September',
        'octobre'   => 'October',
        'oct'       => 'October',
        'novembre'  => 'November',
        'nov'       => 'November',
        'décembre'  => 'December',
        'déc'       => 'December',
    ];

    /*
     |--------------------------------------------------------------------------
     | REVIEW DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | le {day} {month} {year}
     |
     */

    const REVIEW_DATE_REGEX = '/le (?P<day>\d{1,2}) (?P<month>[a-zA-ZÀ-ÿ]+) (?P<year>\d{4})/si';


    /*
     |--------------------------------------------------------------------------
     | QUESTION TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | Affichage de {number}-{number} sur {total} questions
     |
     */

    const QUESTION_TOTAL_REGEX = '/Affichage de \d+-\d+ sur (?P<total>\d+) questions/Uis';

    /*
     |--------------------------------------------------------------------------
     | QUESTION DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | question posée par {author} le {day} {month} {year}
     |
     */

    const QUESTION_DATE_REGEX = '/question posée par (?P<author>.*) le (?P<day>\d{1,2}) (?P<month>[a-zA-ZÀ-ÿ]+) (?P<year>\d{4})/si';

    /*
     |--------------------------------------------------------------------------
     | ANSWER TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | Affichage de {number}-{number} sur {total} réponses
     |
     */

    const ANSWER_TOTAL_REGEX = '/Affichage de \d+-\d+ sur (?P<total>\d+) réponses/Uis';

    /*
     |--------------------------------------------------------------------------
     | ANSWER DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {author} a répondu le {day} {month} {year}
     |
     */

    const ANSWER_DATE_REGEX = '/(?P<author>.*) a répondu le (?P<day>\d{1,2}) (?P<month>[a-zA-ZÀ-ÿ]+) (?P<year>\d{4})/si';
}