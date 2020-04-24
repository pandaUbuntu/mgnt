<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Model\ResourceModel\Message;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * List of messages
     *
     * @var array
     */
    private $messages = [];

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(
            \Oleksandrr\Chat\Model\Message::class,
            \Oleksandrr\Chat\Model\ResourceModel\Message::class
        );
    }

    /**
     * @return array
     */
    public function getCollection()
    {
        return $this->messages;
    }

    /**
     * @param int $websiteId
     * @return Collection
     */
    public function addWebsiteFilter(int $websiteId): self
    {
        return $this->addFieldToFilter('website_id', $websiteId);
    }

    /**
     * @param int $authorId
     * @return Collection
     */
    public function addAuthorFilter(int $authorId): self
    {
        return $this->addFieldToFilter('author_id', $authorId);
    }

    /**
     * @param int $authorTypeId
     * @return Collection
     */
    public function addAuthorTypeFilter(int $authorTypeId): self
    {
        return $this->addFieldToFilter('author_type', $authorTypeId);
    }

    /**
     * @param string $chatHash
     * @return Collection
     */
    public function addChatHashFilter(string $chatHash): self
    {
        return $this->addFieldToFilter('chat_hash', $chatHash);
    }
}
