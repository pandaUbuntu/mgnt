<?php
declare(strict_types=1);

namespace Oleksandrr\Catalog\Controller\FooFoo\Baz;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;

class RedirectExample extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $redirectFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
    ) {
        $this->redirectFactory = $redirectFactory;
        parent::__construct($context);

    }

    /**
     * @inheritDoc
     * "https://alexandrr.local/oleksandrr-catalog-controllers-demo/fooFoo_baz/redirectExample"
     */
    public function execute()
    {
//        $this->redirectFactory->create()->setHttpResponseCode();
        /** @var Redirect $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $response->setHttpResponseCode(301);
        $response->setPath(
            'oleksandrr_catalog/fooFoo_baz/bar',
            [
                '_secure' => true,
                'string_parameter' => 'Redirect',
                'integer_value' => 10,
                'product_cost' => 20,
            ]
        );

        return $response;
    }
}
