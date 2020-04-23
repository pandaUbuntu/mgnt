<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Controller\Message;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const XML_PATH_OLEKSANDRR_CHAT_ENABLED = 'oleksandrr_chat/general/enabled';

    public const XML_PATH_OLEKSANDRR_CHAT_ALLOW_FOR_GUESTS = 'oleksandrr_chat/general/allow_for_guests';

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
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigs
     */
    private $scopeConfig;

    /**
     * Save constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Oleksandrr\Chat\Model\MessageFactory $messageFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Oleksandrr\Chat\Model\ResourceModel\Message $messageResource
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Oleksandrr\Chat\Model\MessageFactory $messageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger,
        \Oleksandrr\Chat\Model\ResourceModel\Message $messageResource,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->messageFactory = $messageFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->messageResource = $messageResource;
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            /** @var \Magento\Framework\App\Request\Http $request */
            $request = $this->getRequest();
            $adminMessage = '';

            if (!$this->validateRequest()) {
                throw new LocalizedException(__('Validation Failed'));
            }

            $userId = (int) $this->customerSession->getId();
            $websiteId = (int) $this->storeManager->getWebsite()->getId();

            /** @var \Oleksandrr\Chat\Model\Message $message */
            $message = $this->messageFactory->create();
            $date = new \DateTime();

            if (!$hash = $this->customerSession->getChatHash()) {
                $hash = md5($date->getTimestamp() . $request->getParam('user_message'));
                $this->customerSession->setChatHash($hash);
            }

            $message
                ->setAuthorId($userId ?: 0)
                ->setAuthorType(1)
                ->setWebsiteId($websiteId)
                ->setMessage($request->getParam('user_message') ?: '')
                ->setAuthorName('Guest')
                ->setChatHash($hash);

            $this->messageResource->save($message);

            $alert = __('Saved!');
            $adminMessage = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu egestas nisi, ut varius dolor.';
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $alert = $e->getMessage();
        }

        /** @var JsonResult $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'message' => $alert,
            'admin_message' => $adminMessage
        ]);

        return $response;
    }

    /**
     * @return bool
     */
    private function validateRequest(): bool
    {
        $request = $this->getRequest();
        $allowSaveMessage = true;

        if (!$this->formKeyValidator->validate($request)) {
            $allowSaveMessage = false;
        }

        if (!$this->scopeConfig->getValue(self::XML_PATH_OLEKSANDRR_CHAT_ENABLED)
            || (!$this->customerSession->isLoggedIn() && !$this->scopeConfig->getValue(self::XML_PATH_OLEKSANDRR_CHAT_ALLOW_FOR_GUESTS))
        ) {
            $allowSaveMessage = false;
        }

        $eventParameters = [
            'allow_saving_messages' => $allowSaveMessage
        ];
        $this->_eventManager->dispatch('oleksandrr_chat_allow_save', $eventParameters);

        return $allowSaveMessage;
    }
}
