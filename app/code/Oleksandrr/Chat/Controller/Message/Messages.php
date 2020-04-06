<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Controller\Message;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;

class Messages extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @var \Oleksandrr\Chat\Model\MessageFactory $messageFactory
     */
    private $messageFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Save constructor.
     * @param \Oleksandrr\Chat\Model\MessageFactory $messageFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Oleksandrr\Chat\Model\MessageFactory $messageFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->messageFactory = $messageFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $list = [];
        $message = '';

        try {
            /** @var \Oleksandrr\Chat\Model\Message $message */
            $message = $this->messageFactory->create();
            $collection = $message->getCollection();

            if ($collection) {
                $collection->setPageSize(10)->setCurPage(1)->load();
                $list = $collection->getData();
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $message = $e->getMessage();
        }

        /** @var JsonResult $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'list' => $list,
            'message' => $message,
        ]);

        return $response;
    }
}
