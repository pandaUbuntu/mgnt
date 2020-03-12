<?php

namespace Oleksandrr\Catalog\Block\Product\View;

/**
 * Class Demo
 * @package Oleksandrr\Catalog\Block\Product\View
 *
 * @method int getIntVar()
 * @method string getStringVar()
 */
class Demo extends \Magento\Catalog\Block\Product\View
{
    /**
     * @return float
     */
    public function getProductSalableQty(): float
    {
        return $this->getProduct()->getQty();
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->getProduct()->getName();
    }
}
