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
            if ($chatHash = $this->userSession->getChatHash()) {
                $user = $observer->getEvent()->getCustomer();
                $messageCollection = $this->messageCollectionFactory->create();
                $messageCollection->addChatHashFilter($chatHash)->addAuthorTypeFilter(\Oleksandrr\Chat\Model\Message::CUSTOMER_TYPE);

                /** @var Message $message */
                foreach ($messageCollection as $message) {
                    if (!$message->getAuthorId()) {
                        $message->setAuthorId($user->getId());
                        $this->messageResource->save($message);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
