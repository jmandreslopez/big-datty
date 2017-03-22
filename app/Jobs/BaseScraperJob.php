<?php namespace App\Jobs;

use App\Helpers\Amazon\ScraperHelper;
use App\Helpers\CrawlerRequest;
use Symfony\Component\DomCrawler\Crawler;
use Exception;

abstract class BaseScraperJob extends BaseJob
{
    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var ScraperHelper
     */
    protected $scraper;

    /**
     * Get DOM Crawler
     *
     * @param string $url
     * @throws Exception
     */
    protected function getCrawler($url)
    {
        $response = CrawlerRequest::getRequest($url);

        if (! empty($response)){
            $this->crawler = new Crawler(null, $url);
            $this->crawler->addContent($response['bodyContent'], $response['headerContentType']);

            if (empty($this->crawler)) {
                throw new Exception('Empty crawler');
            }
        }
    }
}