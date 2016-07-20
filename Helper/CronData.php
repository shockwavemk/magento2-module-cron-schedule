<?php
/**
 * Copyright 2016 Shockwave-Design - J. & M. Kramer, all rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Shockwavemk\CronSchedule\Helper;

use Magento\Framework\App\Helper\Context;
/**
 * CronData provider
 */
class CronData extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $config;
    protected $schedule;

    public function __construct(
        Context $context,
        \Magento\Cron\Model\ConfigInterface $config,
        \Magento\Cron\Model\Schedule $schedule
    )
    {
        parent::__construct($context);

        $this->config = $config;
        $this->schedule = $schedule;
    }

    public function getCronjobData()
    {
        $cronjobSchedules = [];

        $scheduleCollection = $this->schedule->getCollection();

        /** @var \Magento\Cron\Model\Schedule $schedule */
        foreach ($scheduleCollection as $schedule) {
            $newSchedule = [];
            $title = '';

            $title .= 'Created at: ' . $schedule->getCreatedAt() . "\n";
            $title .= 'Scheduled at: ' . $schedule->getScheduledAt() . "\n";

            if($schedule->getStatus() == 'running') {
                $newSchedule['end'] = date('Y-m-d H:i:s');
            }

            if(!empty($schedule->getFinishedAt())) {
                $newSchedule['end'] = $schedule->getFinishedAt();
                $title .= 'Finished at: '. $schedule->getFinishedAt() . "\n";
            }

            $newSchedule['start'] = $schedule->getScheduledAt();
            /** @noinspection IsEmptyFunctionUsageInspection */
            if(!empty($schedule->getExecutedAt())) {
                $newSchedule['start'] = $schedule->getExecutedAt();
                $title .= 'Executed at: '. $schedule->getFinishedAt() . "\n";
            }

            /** @noinspection IsEmptyFunctionUsageInspection */
            if(!empty($schedule->getMessages())) {
                $title .= 'Messages: '. $schedule->getMessages() . "\n";
            }

            $newSchedule['id'] = $schedule->getScheduleId();
            $newSchedule['content'] = $schedule->getStatus();
            $newSchedule['group'] = $schedule->getJobCode();
            $newSchedule['className'] = $schedule->getStatus();
            $newSchedule['title'] = $title;


            $cronjobSchedules[] = $newSchedule;
        }

        return $cronjobSchedules;
    }

    public function getCronjobGroups()
    {
        $cronjobGroups = [];

        $i = 0;

        foreach ($this->config->getJobs() as $job) {
            foreach ($job as $group) {
                $shedule = !empty($group['schedule']) ? $group['schedule'] : '';

                $cronjobGroups[] = [
                    'content' => $group['name'] . '<br/><span style="color:#BBB;">(' . $shedule . ')</span>',
                    'id' => $group['name'],
                    'value' => $i++,
                    'className' => $group['name'],
                    'title' => $group['instance'] . '::' . $group['method']
                ];
            }
        }


        return $cronjobGroups;
    }
}
