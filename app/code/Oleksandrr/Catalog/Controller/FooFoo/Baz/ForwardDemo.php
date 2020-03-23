<?php
declare(strict_types=1);

namespace Oleksandrr\Catalog\Controller\FooFoo\Baz;

use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\ResultFactory;

class ForwardDemo extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @inheritDoc
     * "https://alexandrr.local/oleksandrr-catalog-controllers-demo/fooFoo_baz/forwardDemo"
     */
    public function execute()
    {
        /** @var Forward $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        $response->setParams([
            'first_name' => 'Oleksandr',
            'last_name' => 'Radionov',
            'url' => 'Some Url',
        ])
        ->forward('dataDemo');

        return $response;
    }
}
