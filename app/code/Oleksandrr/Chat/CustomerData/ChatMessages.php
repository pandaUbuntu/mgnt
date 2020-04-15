<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\CustomerData;

use Oleksandrr\Chat\Model\Message;

class ChatMessages implements \Magento\Customer\CustomerData\SectionSourceInterface
{
    /**
     * @var \Magento\Customer\Model\Session $userSession
     */
    private $userSession;

    /**
     * @var \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory
     */
    private $messageCollectionFactory;

    /**
     * CustomerPreferences constructor.
     * @param \Magento\Customer\Model\Session $userSession
     * @param \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory
     */
    public function __construct(
        \Magento\Customer\Model\Session $userSession,
        \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory
    ) {
        $this->userSession = $userSession;
        $this->messageCollectionFactory = $messageCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getSectionData(): array
    {
        $data = [];
        if ($this->userSession->isLoggedIn()) {
            $messageCollection = $this->messageCollectionFactory->create()->addAuthorFilter((int) $this->userSession->getId())->setPageSize(10);

            if (isset($messageCollection)) {
                /** @var Message $message */
                foreach ($messageCollection as $message) {
                    $data[] = [
                        'author_name' => $message->getAuthorName(),
                        'message' => $message->getMessage(),
                        'created_at' => $message->getCreatedAt(),
                    ];
                }
            }
        } else {
            $data = $this->userSession->getData('chat') ?? [];
        }

        return $data;
    }
}
