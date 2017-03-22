<?php namespace App\Services\Product;

use App\Helpers\Amazon\ScraperHelper;
use App\Models\Product\Product;
use App\Models\Product\ProductHijack;
use Symfony\Component\DomCrawler\Crawler;

class ProductHijackService
{
    /**
     * @param Product $product
     * @param ScraperHelper $scraper
     * @param int $sourceId
     * @param Crawler $node
     * @return ProductHijack|bool
     */
    public static function create($product, $scraper, $sourceId, $node)
    {
        //
    }

    /**
     * @param ProductHijack $productHijack
     * @param Crawler $node
     */
    protected static function metadata($productHijack, $node)
    {
        //
    }
}