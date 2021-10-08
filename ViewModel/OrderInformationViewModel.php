<?php

namespace Mathanagopal\OrderSummary\ViewModel;

use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address\Renderer;

class OrderInformationViewModel implements ArgumentInterface
{
    /**
     * @var Session
     */
    protected $checkoutSession;
    /**
     * @var Renderer
     */
    protected $addressRenderer;

    public function __construct(
        Session $checkoutSession,
        Renderer $addressRenderer
    ) {
        $this->checkoutSession    = $checkoutSession;
        $this->addressRenderer    = $addressRenderer;
    }

    public function getOrderSummary(): array
    {
        $order                                = $this->getOrder();
        $orderInformation                     = [];
        $orderInformation['shipping_address'] = $this->addressRenderer->format($order->getShippingAddress(), 'html');
        $orderInformation['shipping_method']  = $order->getShippingDescription()
                                                ?? __('No shipping information available');
        $orderInformation['billing_address']  = $this->addressRenderer->format($order->getBillingAddress(), 'html');
        $orderInformation['payment_method']   = $order->getPayment()
                                                      ->getData('additional_information')['method_title']
                                                ?? __('No Payment information available');

        return $orderInformation;
    }

    public function getOrder(): Order
    {
        return $this->checkoutSession->getLastRealOrder();
    }

}