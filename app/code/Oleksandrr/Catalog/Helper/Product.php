<?php
declare(strict_types=1);

namespace Oleksandrr\Catalog\Helper;

use Magento\Framework\Data\Collection;

class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductCatagoriesCollection(\Magento\Catalog\Model\Product $product): Collection
    {
        return $product->getCategoryCollection()
            ->addAttributeToSelect('name')
            ->addFieldToFilter('level', ['gteq' => 2]);
    }
}