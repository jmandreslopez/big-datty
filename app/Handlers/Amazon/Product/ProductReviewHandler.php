<?php namespace App\Handlers\Amazon\Product;

use App\Handlers\BaseScraperHandler;
use App\Models\Product\Product;

class ProductReviewHandler extends BaseScraperHandler
{
    /**
     * Product Id
     *
     * @var int
     */
    protected $productId;

    /**
     * ASIN
     *
     * @var string
     */
    protected $asin;

    /**
     * Set Product Id
     *
     * @param int|array $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get Product Id
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set ASIN
     *
     * @param string $asin
     * @return $this
     */
    public function setAsin($asin)
    {
        $this->asin = $asin;

        return $this;
    }

    /**
     * Get ASIN
     *
     * @return string
     */
    public function getAsin()
    {
        return $this->asin;
    }

    /**
     * Initialize
     */
    protected function init()
    {
        if (! empty($this->arguments['product'])) {
            $this->setProductId($this->arguments['product']);
        }

        if (! empty($this->arguments['asin'])) {
            $this->setAsin($this->arguments['asin']);
        }
    }

    /**
     * Load the queue
     */
    protected function load()
    {
        $query = Product::select('*');

        if (! empty($this->getProductId())) {
            $query->whereId($this->getProductId());
        }

        if (! empty($this->getAsin())) {
            $query->whereSourceId($this->getAsin());
        }

        $query->chunk(1000, function ($products) {
            foreach ($products as $product) {
                debug('Product Id: ' . $product->id);
                $this->queueProductReview($product->account, $product);
            }
        });
    }
}