<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Observer;

use Magento\Tests\NamingConvention\true\string;
use Oleksandrr\Chat\Model\Message;

class UserLogin implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory
     */
    private $messageCollectionFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Oleksandrr\Chat\Model\ResourceModel\Message
     */
    private $messageResource;

    /**
     * @param \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory
     * @param \Oleksandrr\Chat\Model\ResourceModel\Message $messageResource
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory,
        \Oleksandrr\Chat\Model\ResourceModel\Message $messageResource,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->messageCollectionFactory = $messageCollectionFactory;
        $this->messageResource = $messageResource;
        $this->logger = $logger;
        $this->customerSession = $customerSession;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        try {
            $messageCollection = $this->messageCollectionFactory->create();
            /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
            $customer = $observer->getEvent()->getCustomer();
            $oldChatHash = $this->getLastChatHash((int)$customer->getId());

            if ($chatHash = $this->customerSession->getChatHash()) {
                $messageCollection->addChatHashFilter($chatHash)->addAuthorTypeFilter(\Oleksandrr\Chat\Model\Message::CUSTOMER_TYPE);

                /** @var Message $message */
                foreach ($messageCollection as $message) {
                    if (!$message->getAuthorId()) {
                        $message->setAuthorId($customer->getId());
                        $oldChatHash ? $message->setChatHash($oldChatHash) : null;
                        $this->messageResource->save($message);
                    }
                }
            }

            $oldChatHash ? $this->customerSession->setChatHash($oldChatHash) : null;
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }

    /**
     * @param int $customerId
     * @return string|null
     */
    private function getLastChatHash(int $customerId): ?string
    {
        $messageCollection = $this->messageCollectionFactory->create();

        /** @var Message $message */
        $message = $messageCollection
            ->addAuthorFilter($customerId)
            ->setOrder('created_at', 'DESC')
            ->setPageSize(1)
            ->getFirstItem();
        if ($message->getChatHash()) {
            return $message->getChatHash();
        }

        return null;
    }
}
