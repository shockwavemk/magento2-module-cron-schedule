<?php
/**
 * Copyright 2016 Shockwave-Design - J. & M. Kramer, all rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Shockwavemk\CronSchedule\Block\Adminhtml\Cronschedule\Index;

use Magento\Config\Block\System\Config\Form\Field\Datetime;
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
class Content extends \Magento\Backend\Block\Template
{
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

    protected $config;
    protected $schedule;


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
        \Magento\Cron\Model\ConfigInterface $config,
        \Magento\Cron\Model\Schedule $schedule,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->coreRegistry = $registry;
        $this->dateTime = $dateTime;
        $this->objectManager = $objectManager;
        $this->config = $config;
        $this->schedule = $schedule;
    }

    public function getCronjobData()
    {
        $cronjobSchedules = [];

        $scheduleCollection = $this->schedule->getCollection();

        $i = 0;

        /** @var \Magento\Cron\Model\Schedule $schedule */
        foreach ($scheduleCollection as $schedule) {
            $cronjobSchedules[] = [
                'id' => $i++,
                'content' => $schedule->getStatus(),
                'start' => $schedule->getScheduledAt(),
                'end' => $schedule->getStatus() == 'running' ? date('Y-m-d H:i:s') : $schedule->getFinishedAt(),
                'group' => $schedule->getJobCode(),
                'className' => $schedule->getStatus()
            ];
        }
        // {id: 1, content: 'item 1', start: '2013-04-20', end: new Date()},
        return $cronjobSchedules;
    }

    public function getCronjobGroups()
    {
        $cronjobGroups = [];

        $i = 0;

        foreach ($this->config->getJobs() as $job) {
            foreach ($job as $group) {
                $cronjobGroups[] = [
                    'content' => $group['name'],
                    'id' => $group['name'],
                    'value' => $i++,
                    'className' => $group['name']
                ];
            }
        }

        return $cronjobGroups;
    }
}
