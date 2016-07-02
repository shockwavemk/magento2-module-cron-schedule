<?php
/**
 * Copyright 2016 Shockwave-Design - J. & M. Kramer, all rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Shockwavemk\CronSchedule\Block\Adminhtml\Cronschedule\Index;

/**
 * Adminhtml customer view personal information sales block.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Content extends \Magento\Backend\Block\Template
{
    /** @var \Shockwavemk\CronSchedule\Helper\CronData */
    protected $cronData;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Shockwavemk\CronSchedule\Helper\CronData $cronData
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Shockwavemk\CronSchedule\Helper\CronData $cronData,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->cronData = $cronData;
    }

    public function getCronjobData()
    {
        return $this->cronData->getCronjobData();
    }
    
    public function getCronjobGroups()
    {
        return $this->cronData->getCronjobGroups();
    }
}
