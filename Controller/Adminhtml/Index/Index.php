<?php
/**
 * Copyright 2016 Shockwave-Design - J. & M. Kramer, all rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Shockwavemk\CronSchedule\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action
{
    /**
     * Preview Newsletter template
     *
     * @return void|$this
     * @throws \RuntimeException
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Cronjob timeline'));
        $this->_view->renderLayout();
    }
}
