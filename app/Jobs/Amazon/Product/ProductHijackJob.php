<?php namespace App\Jobs\Amazon\Product;

use App\Helpers\Amazon\ScraperHelper;
use App\Jobs\BaseScraperJob;

class ProductHijackJob extends BaseScraperJob
{
    /**
     * Initialize Job
     */
    protected function init()
    {
        $this->product = $this->attributes['product'];

        $country = $this->account->marketplace->country_code;
        $this->scraper = ScraperHelper::getScraper($country);
    }

    protected function process()
    {
        // TODO: Implement process() method.
    }

//    /**
//     * @param Product $product
//     * @param int $page
//     * @param int $tries
//     */
//    protected function getHijacks($product, $page = 1, $tries = 0)
//    {
//        $domain = $product->account->marketplace->domain;
//        $base = 'http://' . $domain . '/gp/offer-listing/';
//        $params = [
//            'ie'        => 'UTF8',
//            'condition' => 'new',
//        ];
//
//        $url = $base . $product->source_id . '/ref=olp_tab_new?' . http_build_query($params);
//
//        $crawler = $this->getCrawler($url);
//        if (! empty($crawler)) {
//            $crawler->filter('#olpOfferList .olpOffer')->each(function(Crawler $node) use ($product) {
//
//                // Price
//                $price = $node->filter('.olpOfferPrice')->text();
//
//                // Condition
//                $condition = $node->filter('.olpCondition')->text();
//
//                // Seller
//                $raw = $node->filter('.olpSellerName > a')->attr('href');
//
//                $country = $product->account->marketplace->country_code;
//                $scraper = ScraperHelper::getScraper($country);
//
//                preg_match($scraper::HIJACK_SELLER_ID_REGEX, $raw, $seller);
//
//                //                d([
//                //                    'price'     => trim($price),
//                //                    'condition' => trim($condition),
//                //                    'seller'    => $seller['id'],
//                //                ]);
//
//                $details = $product->account->details();
//                if ($seller['id'] != $details['SellerId']) {
//                    debug($seller['id']);
//                }
//            });
//        }


        /**
         * @ToDo Change this to DomCrawler
         */

        //        $response = CrawlerRequest::getRequest($url);
        //
        //        if (stripos($response['bodyContent'], '404 - Document Not Found') == false) {
        //
        //            preg_match_all('/olpOfferPrice.*>(.*)<\/span>.*olpCondition(.*)<.*olpSellerName.*<a.*seller=([A-Z0-9]+)">(.*)<\/a>/Us', $response['bodyContent'], $matches, PREG_SET_ORDER);
        //
        //            $page_clones = [];
        //            foreach ($matches as $match) {
        //                $cloner = trim($match[3]);
        //
        //                if ($cloner != $details['SellerId'] && !in_array($cloner, $page_clones)) {
        //                    $page_clones[] = $cloner;
        //                }
        //            }
        //
        //            if (count($page_clones) > 0)
        //            {
        //                // Compare the data we have with whats in the DB
        //                $db_clones = (array) json_decode($product_data->clones, true);
        //                $ids = array();
        //
        //                $new_clones = array();
        //                foreach($page_clones as $cloner) {
        //                    if(!in_array($cloner, $db_clones)) {
        //                        $new_clones[] = $cloner;
        //                    }
        //                }
        //
        //
        //                // Send an email report with the new clones that were found if any
        //                if (!empty($new_clones)) {
        //                    $this->hijack_report_send($new_clones, $product_data);
        //                    $this->report_writer->set_message("Clones Found For These ASIN <br><b>".$product_data->asin."</b><br>");
        //
        //                    //insert all the clones to the DB
        //                    $page_clones = array_merge($db_clones, $new_clones);
        //                    $encode_clonedata = json_encode($page_clones, true);
        //                    $hijack_data['clones'] = $encode_clonedata;
        //                    $hijack_where['asin'] = $product_data->asin;
        //
        //                    $this->products->hijacker_add_clones($hijack_data, $hijack_where);
        //                    $this->report_writer->set_message("Hi-jack Clones Added to the DB <br><b>".$product_data->asin."</b><br>");
        //
        //                    // Set the statistic report for hi_jacks
        //                    $stats_datestring = "%M-%d-%Y";
        //                    $stats_time = time();
        //                    $stats_date = mdate($stats_datestring, $stats_time);
        //                    $stats_db_result = $this->health_center_mdl->watchdog_stats_getbydate($stats_date);
        //
        //                    if ($stats_db_result == TRUE) {
        //                        $count = $stats_db_result->hi_jacks	 + '1';
        //                        $data = array(
        //                            'hi_jacks' => $count
        //                        );
        //                        // update stats count +1
        //                        $this->health_center_mdl->watchdog_stats_update($data, $stats_date);
        //                    } else {
        //                        $data = array(
        //                            'day' => $stats_date,
        //                            'hi_jacks' => '1'
        //                        );
        //                        //add to stats count 1
        //                        $this->health_center_mdl->watchdog_stats_set($data);
        //                    }
        //
        //                }
        //
        //                // Set the statistic report
        //                $datestring = "%M-%d-%Y";
        //                $time = time();
        //                $date = mdate($datestring, $time);
        //
        //                $result = $this->health_center_mdl->watchdog_stats_getbydate($date);
        //
        //                // Set the statistic report for hijacks_datascrape
        //                $stats_datestring = "%M-%d-%Y";
        //                $stats_time = time();
        //                $stats_date = mdate($stats_datestring, $stats_time);
        //                $stats_db_result = $this->health_center_mdl->watchdog_stats_getbydate($stats_date);
        //
        //                if ($stats_db_result == TRUE) {
        //                    $count = $stats_db_result->hijacks_datascrape + '1';
        //                    $data = array(
        //                        'hijacks_datascrape' => $count
        //                    );
        //                    // update stats count +1
        //                    $this->health_center_mdl->watchdog_stats_update($data, $stats_date);
        //                } else {
        //                    $data = array(
        //                        'day' => $stats_date,
        //                        'hijacks_datascrape' => '1'
        //                    );
        //                    //add to stats count 1
        //                    $this->health_center_mdl->watchdog_stats_set($data);
        //                }
        //
        //            }
        //        }
//    }
}