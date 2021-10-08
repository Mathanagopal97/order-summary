<?php

namespace Mathanagopal\OrderSummary\Helper;

use Magento\Framework\Pricing\Helper\Data;

class OrderSummaryHelper
{
    /**
     * @var Data
     */
    protected $formatPrice;

    public function __construct(Data $formatPrice)
    {
        $this->formatPrice = $formatPrice;
    }

    public function formatPrice($price){
        return $this->formatPrice->currency($price, true, false);
    }
}