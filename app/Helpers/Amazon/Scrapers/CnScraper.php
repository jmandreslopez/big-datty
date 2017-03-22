<?php namespace App\Helpers\Amazon\Scrapers;

use App\Helpers\Amazon\ScraperHelper;

class CnScraper extends ScraperHelper
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
     | 于 {year}年{month}月{day}日
     |
     */

    const REVIEW_DATE_REGEX = '/于 (?P<year>\d{4})年(?P<month>\d{1,2})月(?P<day>\d{1,2})日/si';


    /*
     |--------------------------------------------------------------------------
     | QUESTION TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | 正在显示 {total} 中的 {number} 至 {number} 个问题
     |
     */

    const QUESTION_TOTAL_REGEX = '/正在显示 (?P<total>\d+) 中的 \d+ 至 \d+ 个问题/Uis';

    /*
     |--------------------------------------------------------------------------
     | QUESTION DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | 作者 {customer} 日期 年月{日
     | 在 {year} {month}月 day} 由 {author} 提问
     |
     */

    const QUESTION_DATE_REGEX = '/在 (?P<year>\d{2}) (?P<month>\d{1,2})月 (?P<day>\d{1,2}) 由 (?P<author>.*) 提问/si';

    /*
     |--------------------------------------------------------------------------
     | ANSWER TOTAL REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | 正在显示 {total} 中的 {number} 至 {number} 个答案
     |
     */

    const ANSWER_TOTAL_REGEX = '/正在显示 (?P<total>\d+) 中的 \d+ 至 \d+ 个答案/Uis';

    /*
     |--------------------------------------------------------------------------
     | ANSWER DATE REGEX PATTERN
     |--------------------------------------------------------------------------
     |
     | {author} 在 {year} {month}月 {day} 进行了回答
     |
     */

    const ANSWER_DATE_REGEX = '/(?P<author>.*) 在 (?P<year>\d{2}) (?P<month>\d{1,2})月 (?P<day>\d{1,2}) 进行了回答/si';
}