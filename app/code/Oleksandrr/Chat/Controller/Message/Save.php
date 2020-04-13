<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Controller\Message;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;

class Save extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * @var \Oleksandrr\Chat\Model\MessageFactory $messageFactory
     */
    private $messageFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Oleksandrr\Chat\Model\ResourceModel\Message
     */
    private $messageResource;

    /**
     * Save constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Oleksandrr\Chat\Model\MessageFactory $messageFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Oleksandrr\Chat\Model\ResourceModel\Message $messageResource
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Oleksandrr\Chat\Model\MessageFactory $messageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger,
        \Oleksandrr\Chat\Model\ResourceModel\Message $messageResource
    ) {
        parent::__construct($context);
        $this->messageFactory = $messageFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->messageResource = $messageResource;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            /** @var \Magento\Framework\App\Request\Http $request */
            $request = $this->getRequest();
            $websiteId = (int) $this->storeManager->getWebsite()->getId();

            /** @var \Oleksandrr\Chat\Model\Message $message */
            $message = $this->messageFactory->create();

            $message->setAuthorId(1)
                ->setAuthorType(1)
                ->setWebsiteId($websiteId)
                ->setMessage($request->getParam('user_message') ?: '')
                ->setAuthorName('Guest');

            $this->messageResource->save($message);

            $alert = __('Saved!');
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $alert = $e->getMessage();
        }

        /** @var JsonResult $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'message' => $alert,
            'admin_message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu egestas nisi, ut varius dolor. '
        ]);

        return $response;
    }
}
