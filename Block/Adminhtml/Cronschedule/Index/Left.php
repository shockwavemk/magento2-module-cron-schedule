<?php
/**
 * Copyright 2016 Shockwave-Design - J. & M. Kramer, all rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Shockwavemk\CronSchedule\Block\Adminhtml\Mail\Edit;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Customer\Model\Address\Mapper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Adminhtml customer view personal information sales block.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Left extends \Magento\Backend\Block\Template
{
    /**
     * Interval in minutes that shows how long customer will be marked 'Online'
     * since his last activity. Used only if it's impossible to get such setting
     * from configuration.
     */
    const DEFAULT_ONLINE_MINUTES_INTERVAL = 15;

    /**
     * Customer
     *
     * @var \Magento\Customer\Api\Data\CustomerInterface
     */
    protected $customer;

    /**
     * Customer log
     *
     * @var \Magento\Customer\Model\Log
     */
    protected $customerLog;

    /**
     * Customer logger
     *
     * @var \Magento\Customer\Model\Logger
     */
    protected $customerLogger;

    /**
     * Account management
     *
     * @var AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * Customer group repository
     *
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * Customer data factory
     *
     * @var \Magento\Customer\Api\Data\CustomerInterfaceFactory
     */
    protected $customerDataFactory;

    /**
     * Address helper
     *
     * @var \Magento\Customer\Helper\Address
     */
    protected $addressHelper;

    /**
     * Date time
     *
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * Address mapper
     *
     * @var Mapper
     */
    protected $addressMapper;

    /**
     * Data object helper
     *
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;


    /** @var \Shockwavemk\Mail\Base\Model\Mail _mail */
    protected $_mail;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param AccountManagementInterface $accountManagement
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory
     * @param \Magento\Customer\Helper\Address $addressHelper
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Framework\Registry $registry
     * @param Mapper $addressMapper
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Customer\Model\Logger $customerLogger
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        AccountManagementInterface $accountManagement,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory,
        \Magento\Customer\Helper\Address $addressHelper,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\Registry $registry,
        Mapper $addressMapper,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Customer\Model\Logger $customerLogger,
        \Magento\Framework\ObjectManagerInterface $manager,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->coreRegistry = $registry;
        $this->accountManagement = $accountManagement;
        $this->groupRepository = $groupRepository;
        $this->customerDataFactory = $customerDataFactory;
        $this->addressHelper = $addressHelper;
        $this->dateTime = $dateTime;
        $this->addressMapper = $addressMapper;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->customerLogger = $customerLogger;
        $this->manager = $manager;

        $mailId = $this->_request->getParam('id');
        $this->_mail = $manager->get('\Shockwavemk\Mail\Base\Model\Mail');
        $this->_mail->load($mailId);
    }

    public function getMail()
    {
        return $this->_mail;
    }

    public function getStoreName()
    {
        return $this->_storeManager->getStore($this->_mail->getStoreId())->getName();
    }

    public function htmlEscape($text)
    {
        return $text;
    }

    public function getDownloadUrl(\Shockwavemk\Mail\Base\Model\Mail\Attachment $attachment)
    {
        return $this->getUrl('customer/mail/attachment',
            array(
                'id' => base64_encode($this->getMail()->getId()),
                'path' => base64_encode($attachment->getFilePath())
            )
        );
    }
}
