<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Controller\Message;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;

class ChatMessage extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @inheritDoc
     * "https://alexandrr.local/oleksandrr-chat-controllers-message/message/userMessage/some%20string"
     */
    public function execute()
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();

        /** @var Json $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'admin_message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu egestas nisi, ut varius dolor. '
        ]);

        return $response;
    }
}
