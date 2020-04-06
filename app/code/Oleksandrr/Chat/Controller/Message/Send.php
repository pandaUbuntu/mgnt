<?php
declare(strict_types=1);

namespace Oleksandrr\Chat\Controller\Message;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\DB\Transaction;

class Send extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * @var \Oleksandrr\Chat\Model\MessageFactory $messageFactory
     */
    private $messageFactory;

    /**
     * @var  \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory
     */
    private $messageCollectionFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Save constructor.
     * @param \Oleksandrr\Chat\Model\MessageFactory $messageFactory
     * @param \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Oleksandrr\Chat\Model\MessageFactory $messageFactory,
        \Oleksandrr\Chat\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->messageFactory = $messageFactory;
        $this->messageCollectionFactory = $messageCollectionFactory;
        $this->transactionFactory = $transactionFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Transaction $transaction */
        $transaction = $this->transactionFactory->create();
        try {
            /** @var \Magento\Framework\App\Request\Http $request */
            $request = $this->getRequest();
            $websiteId = (int) $this->storeManager->getWebsite()->getId();

            $messageCollection = $this->messageCollectionFactory->create();
            $messageCollection->addAuthorFilter(1)
                ->addWebsiteFilter($websiteId);

            /** @var \Oleksandrr\Chat\Model\Message $message */
            $message = $this->messageFactory->create();

            $message->setAuthorId(1)
                ->setAuthorType(1)
                ->setWebsiteId($websiteId)
                ->setMessage($request->getParam('user_message') ?: '')
                ->setAuthorName('Guest');

            $transaction->addObject($message);

            $transaction->save();
            $message = __('Saved!');
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $message = $e->getMessage();
        }

        /** @var JsonResult $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'message' => $message,
            'admin_message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu egestas nisi, ut varius dolor. '
        ]);

        return $response;
    }
}
