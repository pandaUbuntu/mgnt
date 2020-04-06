<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Model\ResourceModel;

class Message extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('oleksandrr_chat_messages', 'message_id');
    }
}
