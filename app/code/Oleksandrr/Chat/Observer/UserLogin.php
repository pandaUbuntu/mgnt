<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Observer;

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
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $messageCollection = $this->messageCollectionFactory->create();
            /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
            $customer = $observer->getEvent()->getCustomer();

            if ($chatHash = $this->customerSession->getChatHash()) {
                $messageCollection->addChatHashFilter($chatHash)->addAuthorTypeFilter(\Oleksandrr\Chat\Model\Message::CUSTOMER_TYPE);

                /** @var Message $message */
                foreach ($messageCollection as $message) {
                    if (!$message->getAuthorId()) {
                        $message->setAuthorId($customer->getId());
                        $this->messageResource->save($message);
                    }
                }
            } else {
                /** @var Message $message */
                $message = $messageCollection
                    ->addAuthorFilter((int)$customer->getId())
                    ->setOrder('created_at', 'DESC')
                    ->setCurPage(1)
                    ->getFirstItem();
                if ($message->getChatHash()) {
                    $this->customerSession->setChatHash($message->getChatHash());
                }
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
