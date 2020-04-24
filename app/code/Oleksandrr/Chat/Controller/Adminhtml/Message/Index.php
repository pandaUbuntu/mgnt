<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Controller\Adminhtml\Message;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Oleksandrr_Chat::listing';

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
