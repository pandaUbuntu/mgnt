<?php
declare(strict_types=1);

namespace Oleksandrr\Catalog\Controller\FooFoo\Baz;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;

class ForwardDemo extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory $forwardFactory
     */
    protected $forwardFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\ForwardFactory $forwardFactory
    ) {
        parent::__construct($context);
        $this->forwardFactory = $forwardFactory;
    }

    /**
     * @inheritDoc
     * "https://alexandrr.local/oleksandrr-catalog-controllers-demo/fooFoo_baz/forwardDemo"
     */
    public function execute()
    {
        $foo = false;
        $response = $this->forwardFactory->create();
        $response
            ->setParams(
                [
                    'firstName' => 'Oleksandr',
                    'lastName' => 'Radionov',
                    'url' => 'Some Url',
                ]
            )
            ->forward('dataDemo');

        return $response;
    }
}
