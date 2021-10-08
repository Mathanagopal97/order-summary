<?php

namespace Mathanagopal\OrderSummary\ViewModel;

use Magento\Checkout\Model\Session;
use Magento\Framework\Pricing\Helper\Data;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Mathanagopal\OrderSummary\Helper\OrderSummaryHelper;

class ProductInformationViewModel implements ArgumentInterface
{
    /**
     * @var Session
     */
    protected $checkoutSession;
    /**
     * @var OrderSummaryHelper
     */
    protected $orderSummaryHelper;

    public function __construct(
        Session $checkoutSession,
        OrderSummaryHelper $orderSummaryHelper
    ) {
        $this->checkoutSession    = $checkoutSession;
        $this->orderSummaryHelper = $orderSummaryHelper;
    }

    public function getOrderedProductsData(): array
    {
        $order           = $this->getOrder();
        $productsOrdered = $order->getItemsCollection();
        $productDetails  = [];
        /** @var Item $item */
        foreach ($productsOrdered as $item) {
            if ($item->getParentItem()) {
                continue;
            }
            $productDetail                   = [];
            $productDetail['name']           = $item->getName();
            $productDetail['sku']            = $item->getSku();
            $productDetail['price']          = $this->orderSummaryHelper->formatPrice($item->getPrice());
            $productDetail['qty']            = $item->getQtyOrdered();
            $productDetail['subtotal']       = $this->orderSummaryHelper->formatPrice($item->getRowTotal());
            $productDetail['productOptions'] = $this->getItemOptions($item);
            $productDetails[]                = $productDetail;
        }

        return $productDetails;
    }

    public function getOrder(): Order
    {
        return $this->checkoutSession->getLastRealOrder();
    }

    public function getItemOptions(Item $item): array
    {
        $result = [];
        $option = $item->getProductOptions();
        if ($option) {
            if (isset($option['options'])) {
                $result = array_merge($result, $option['options']);
            }
            if (isset($option['additional_options'])) {
                $result = array_merge($result, $option['additional_options']);
            }
            if (isset($option['attributes_info'])) {
                $result = array_merge($result, $option['attributes_info']);
            }
        }

        return $result;
    }
}