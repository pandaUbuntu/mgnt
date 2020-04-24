<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Controller\Message;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;
use Oleksandrr\Chat\Model\Message;

class Messages extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @var \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory
     */
    private $messageCollectionFactory;

    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    private $logger;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * Save constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Model\Session $customerSession
    ) {
        parent::__construct($context);
        $this->messageCollectionFactory = $messageCollectionFactory;
        $this->logger = $logger;
        $this->customerSession = $customerSession;
    }

    public function execute()
    {
        $messageList = [];
        $message = '';

        try {
            $chatHash = $this->customerSession->getChatHash();

            if ($chatHash) {
                $messageCollection = $this->messageCollectionFactory->create()->addChatHashFilter($chatHash);

                /** @var Message $message */
                foreach ($messageCollection as $message) {
                    $messageList[] = [
                        'author_name' => $message->getAuthorName(),
                        'message' => $message->getMessage(),
                        'created_at' => $message->getCreatedAt(),
                    ];
                }

                $message = 'Message count: ' . count($messageList);
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $message = $e->getMessage();
        }

        /** @var JsonResult $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'list' => $messageList,
            'message' => $message,
        ]);

        return $response;
    }
}
