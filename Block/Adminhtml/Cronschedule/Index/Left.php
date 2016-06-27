<?php
/**
 * Copyright 2016 Shockwave-Design - J. & M. Kramer, all rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Shockwavemk\CronSchedule\Block\Adminhtml\Cronschedule\Index;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Customer\Model\Address\Mapper;
use Magento\Framework\Api\DataObjectHelper;
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
     * Customer group repository
     *
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $groupRepository;

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
     * Data object helper
     *
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;
    
    protected $objectManager;


    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Framework\Registry $registry
     * @param DataObjectHelper $dataObjectHelper
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\Registry $registry,
        DataObjectHelper $dataObjectHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->coreRegistry = $registry;
        $this->dateTime = $dateTime;
        $this->objectManager = $objectManager;
    }

    public function getDownloadUrl()
    {
        return $this->getUrl('customer/mail/attachment',
            array(
                'id' => base64_encode('todo')
            )
        );
    }
}
