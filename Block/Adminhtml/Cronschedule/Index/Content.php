<?php
/**
 * Copyright 2016 Shockwave-Design - J. & M. Kramer, all rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Shockwavemk\CronSchedule\Block\Adminhtml\Cronschedule\Index;

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
class Content extends \Magento\Backend\Block\Widget\Form\Container
{
    /** @var \Shockwavemk\Mail\Base\Model\Mail _mail */
    protected $_mail;

    /**
     * @param \Magento\Backend\Block\Template\Context|\Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $manager
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\ObjectManagerInterface $manager,
        array $data = []
    ) {
        $this->_blockGroup = 'Shockwavemk_Mail_Base';
        $this->_controller = 'adminhtml_mail';
        $this->_mode = 'edit';
        $this->_request = $context->getRequest();
        $mailId = $this->_request->getParam('id');
        $this->_mail = $manager->get('\Shockwavemk\Mail\Base\Model\Mail');
        $this->_mail->load($mailId);

        parent::__construct($context, $data);
    }

    /**
     * Prepare the layout.
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->buttonList->remove('save');
        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');

        $this->buttonList->add(
            'send',
            [
                'label' => __('Send mail ...'),
                'onclick' => "setLocation('{$this->getUrl('*/*/send', array('id' => $this->_mail->getId()))}')",
                'class' => 'task'
            ]
        );

        $this->buttonList->add(
            'send_post',
            [
                'label' => __('Resend mail'),
                'onclick' => "setLocation('{$this->getUrl('*/*/sendPost', array('id' => $this->_mail->getId()))}')",
                'class' => 'task'
            ]
        );

        return parent::_prepareLayout();
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        if(!empty($this->_mail->getCustomerId())) {
            return $this->getUrl('customer/index/edit', array('id' => $this->_mail->getCustomerId()));
        }

        return $this->getUrl('index/index');
    }

    public function getMail()
    {
        return $this->_mail;
    }

    public function getUnstructuredData()
    {
        return
            "<h3>Variables</h3>{$this->getVarsOutput()}" .
            "<h3>Recipient variables</h3>{$this->getRecipientVariablesOutput()}" .
            "<h3>Options</h3>{$this->getOptionsOutput()}";
    }

    /**
     * TODO
     *
     * @param $key
     * @param $value
     * @return array
     */
    public function getCustomerRepresentation($key, $value)
    {
        /** @var \Magento\Customer\Model\Customer $value */
        $url = $this->getUrl('customer/index/edit', array('id' => $value->getId()));
        return "{$key} (customer): <a href='{$url}' target='_blank'>{$value->getFirstname()} {$value->getLastname()}</a>";
    }

    /**
     * TODO
     *
     * @param $key
     * @param $value
     * @return array
     */
    public function getOrderRepresentation($key, $value)
    {
        /** @var \Magento\Customer\Model\Customer $value */
        $url = $this->getUrl('sales/order/view', array('order_id' => $value->getId()));
        return "{$key} (order): <a href='{$url}' target='_blank'>{$value->getIncrementId()}</a>";
    }

    /**
     * @param $key
     * @param $value
     * @return string
     */
    public function getLinkRepresentation($key, $value)
    {
        return "{$key} (link): <a href='{$value}' target='_blank'>{$value}</a>";
    }

    /**
     * @param $key
     * @param $value
     * @return string
     */
    public function getStoreRepresentation($key, $value)
    {
        /** @var \Magento\Store\Model\Store $value */
        return "{$key} (store): {$value->getName()}";
    }

    /**
     * @param $key
     * @param $variable
     * @return string
     */
    public function getVarOutput($key, $variable)
    {
        /** @var \Magento\Framework\Model\AbstractModel $variable */
        if (!is_string($variable) && $variable->getEntityType() == 'customer') {
            return $this->getCustomerRepresentation($key, $variable);

        } elseif (!is_string($variable) && $variable->getEntityType() == 'order') {
            return $this->getOrderRepresentation($key, $variable);

        } elseif (!is_string($variable) && $variable->getEntityType() == 'store') {
            return $this->getStoreRepresentation($key, $variable);

        } elseif (is_subclass_of($variable, 'Magento\Framework\Model\AbstractModel')) {
            return $key . ' : ' . var_export($variable->getData(), true);

        } elseif (!filter_var($variable, FILTER_VALIDATE_URL) === false) {
            return $this->getLinkRepresentation($key, $variable);

        } elseif (is_string($variable)) {
            return $key . ' : ' . $variable;
        }

        return $key;
    }

    /**
     * @return string
     */
    public function getVarsOutput()
    {
        $output = [];
        $vars = $this->_mail->getVars();
        if (!empty($vars)) {
            foreach ($vars as $key => $value) {
                $output[] = $this->getVarOutput($key, $value);
            }
        }
        return implode(',<br>', $output);
    }

    /**
     * @return string
     */
    public function getRecipientVariablesOutput()
    {
        $output = [];
        $recipientVariables = $this->_mail->getRecipientVariables();
        if (!empty($recipientVariables)) {
            $output[] = $recipientVariables;
        }
        return implode(',<br>', $output);
    }

    /**
     * @return string
     */
    public function getOptionsOutput()
    {
        $output = [];
        $options = $this->_mail->getOptions();
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $output[] = $this->getVarOutput($key, $value);
            }
        }
        return implode(',<br>', $output);
    }
}
