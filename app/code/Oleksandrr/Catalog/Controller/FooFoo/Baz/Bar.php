<?php
declare(strict_types=1);

namespace Oleksandrr\Catalog\Controller\FooFoo\Baz;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Json;

class Bar extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{

    /**
     * @inheritDoc
     * "https://alexandrr.local/oleksandrr-catalog-controllers-demo/fooFoo_baz/bar/string_parameter/some%20string/integer_value/12"
     */
    public function execute()
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();
        $request->getFullActionName();

        /** @var Json $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'stringParameter' => $request->getParam('string_parameter'),
            'integerValue' => (int)$request->getParam('integer_value'),
            'productCost' => (float)$request->getParam('product_cost', 1),
        ]);

        return $response;
    }
}
