<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Observer;

use Oleksandrr\Chat\Model\Message;

class UserLogin implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $userSession;

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
     * @param \Magento\Customer\Model\Session $userSession
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory,
        \Oleksandrr\Chat\Model\ResourceModel\Message $messageResource,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Model\Session $userSession
    ) {
        $this->messageCollectionFactory = $messageCollectionFactory;
        $this->messageResource = $messageResource;
        $this->logger = $logger;
        $this->userSession = $userSession;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $messageCollection = $this->messageCollectionFactory->create();
            /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
            $customer = $observer->getEvent()->getCustomer();

            if ($chatHash = $this->userSession->getChatHash()) {
                $messageCollection->addChatHashFilter($chatHash)->addAuthorTypeFilter(1);

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
                    $this->userSession->setChatHash($message->getChatHash());
                }
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
