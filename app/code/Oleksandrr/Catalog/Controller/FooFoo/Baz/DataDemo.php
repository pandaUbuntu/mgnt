<?php
declare(strict_types=1);

namespace Oleksandrr\Catalog\Controller\FooFoo\Baz;

use Magento\Framework\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;

class DataDemo extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @inheritDoc
     * https://alexandrr.local/oleksandrr-catalog-controllers-demo/fooFoo_baz/dataDemo
     */
    public function execute()
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();
        $xmlFileName = $request->getFullActionName();
        /** @var Page $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        return $response;
    }
}
