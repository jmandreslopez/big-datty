<?php namespace App\Helpers\Amazon\Scrapers;

use App\Helpers\Amazon\ScraperHelper;

class JpScraper extends ScraperHelper
{
    /*
     |--------------------------------------------------------------------------
     | MONTHS
     |--------------------------------------------------------------------------
     */

    const MONTHS = [
        '1'  => 'January',
        '2'  => 'February',
        '3'  => 'March',
        '4'  => 'April',
        '5'  => 'May',
        '6'  => 'June',
        '7'  => 'July',
        '8'  => 'August',
        '9'  => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ];

    /*
     |--------------------------------------------------------------------------
     | REVIEW DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {year}年{month}月{day}日
     |
     */

    const REVIEW_DATE_REGEX = '/(?P<year>\d{4})年(?P<month>\d{1,2})月(?P<day>\d{1,2})日/si';

    /*
     |--------------------------------------------------------------------------
     | QUESTION TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {total}件の質問中{number}-{number}を表示
     |
     */

    const QUESTION_TOTAL_REGEX = '/(?P<total>\d+)件の質問中\d+-\d+を表示/Uis';

    /*
     |--------------------------------------------------------------------------
     | QUESTION DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {year}/{month}/{day}に{author}さんが質問しました
     |
     */

    const QUESTION_DATE_REGEX = '/(?P<year>\d{4})\/(?P<month>\d{1,2})\/(?P<day>\d{1,2})に(?P<author>.*)さんが質問しました/si';

    /*
     |--------------------------------------------------------------------------
     | ANSWER TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {total}件の質問中{number}-{number}を表示
     |
     */

    const ANSWER_TOTAL_REGEX = '/(?P<total>\d+)件の回答中\d+-\d+を表示/Uis';

    /*
     |--------------------------------------------------------------------------
     | ANSWER DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {author} {year}/{month}/{day}に回答
     |
     */

    const ANSWER_DATE_REGEX = '/(?P<author>.*) (?P<year>\d{4})\/(?P<month>\d{1,2})\/(?P<day>\d{1,2})に回答/si';
}