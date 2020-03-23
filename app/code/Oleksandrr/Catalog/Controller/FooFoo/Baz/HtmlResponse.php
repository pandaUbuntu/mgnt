<?php
declare(strict_types=1);

namespace Oleksandrr\Catalog\Controller\FooFoo\Baz;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;

class HtmlResponse extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $redirectFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
    ) {
        parent::__construct($context);
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @inheritDoc
     * "https://alexandrr.local/oleksandrr-catalog-controllers-demo/fooFoo_baz/htmlResponse"
     */
    public function execute()
    {

        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();
        $xmlFileName = $request->getFullActionName();
//        $this->redirectFactory->create()->setHttpResponseCode();
        /** @var Page $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_PAGE);


        return $response;
    }
}
