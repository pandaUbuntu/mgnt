<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Model;

use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;

/**
 * @method int getMessageId()
 * @method $this setMessageId(int $messageId)
 * @method int getAuthorId()
 * @method $this setAuthorId(int $authorId)
 * @method int getWebsiteId()
 * @method $this setWebsiteId(int $websiteId)
 * @method string getAuthorName()
 * @method $this setAuthorName(string $authorName)
 * @method string getMessage()
 * @method $this setMessage(string $message)
 * @method string getChatHash()
 * @method $this setChatHash(string $chatHash)
 * @method int getAuthorType()
 * @method $this setAuthorType(int $authorType)
 */
class Message extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Customer type
     */
    const CUSTOMER_TYPE = 1;

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(\Oleksandrr\Chat\Model\ResourceModel\Message::class);
    }

    /**
     * Validate customer_id, website_id and attribute_id before saving data
     * Do not fill in data automatically not to break install scripts or import/export that may work from the
     * crontab area or from the CLI - e.g. follow the "Let it fail" principle
     *
     * @return $this
     * @throws LocalizedException
     */
    public function beforeSave(): self
    {
        parent::beforeSave();
        $this->validate();

        return $this;
    }

    /**
     * Ensure that attribute_id is set and is a product attribute.
     * Allow overriding this method via plugins by making it public.
     * This method must be called in the ::beforeSave() method to guarantee that it is executed.
     * Moving the call to a controller means that somebody will forget to do the same in other place.
     * Write error-prone code!
     *
     * @throws LocalizedException
     */
    public function validate(): void
    {
        if (!$this->getWebsiteId()) {
            throw new LocalizedException(__('Can\'t save chat message: %s is not set.', 'website_id'));
        }

        if (!$this->getMessage()) {
            throw new LocalizedException(__('Message is empty'));
        }
    }
}
