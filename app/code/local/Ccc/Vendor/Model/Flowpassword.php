<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Ccc_Vendor
 * @copyright  Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Vendor flow password info Model
 *
 * @category    Mage
 * @package     Ccc_Vendor
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Ccc_Vendor_Model_Flowpassword extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('vendor/flowpassword');
    }

    /**
     * Prepare data before save
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        $this->_prepareData();
        return parent::_beforeSave();
    }

    /**
     * Prepare vendor flow password data
     *
     * @return Ccc_Vendor_Model_Flowpassword
     */
    protected function _prepareData()
    {
        $validatorData = Mage::getSingleton('vendor/session')->getValidatorData();
        $this->setIp($validatorData[Ccc_Vendor_Model_Session::VALIDATOR_REMOTE_ADDR_KEY])
            ->setRequestedDate(Mage::getModel('core/date')->date());
        return $this;
    }

    /**
     * Check forgot password requests to times per 24 hours from 1 e-mail
     *
     * @param string $email
     * @return bool
     */
    public function checkVendorForgotPasswordFlowEmail($email)
    {
        $helper = Mage::helper('vendor');
        $checkForgotPasswordFlowTypes = array(
            Ccc_Adminhtml_Model_System_Config_Source_Vendor_Forgotpassword::FORGOTPASS_FLOW_IP_EMAIL,
            Ccc_Adminhtml_Model_System_Config_Source_Vendor_Forgotpassword::FORGOTPASS_FLOW_EMAIL
        );

        if (in_array($helper->getVendorForgotPasswordFlowSecure(), $checkForgotPasswordFlowTypes)) {
            $forgotPassword = $this->getCollection()
                ->addFieldToFilter('email', array('eq' => $email))
                ->addFieldToFilter(
                    'requested_date',
                    array('gt' => Mage::getModel('core/date')->date(null, '-1 day'))
                );

            if ($forgotPassword->getSize() > $helper->getVendorForgotPasswordEmailTimes()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check forgot password requests to times per hour from 1 IP
     *
     * @return bool
     */
    public function checkVendorForgotPasswordFlowIp()
    {
        $helper        = Mage::helper('vendor');
        $validatorData = Mage::getSingleton('vendor/session')->getValidatorData();
        $remoteAddr    = $validatorData[Ccc_Vendor_Model_Session::VALIDATOR_REMOTE_ADDR_KEY];
        $checkForgotPasswordFlowTypes = array(
            Ccc_Adminhtml_Model_System_Config_Source_Vendor_Forgotpassword::FORGOTPASS_FLOW_IP_EMAIL,
            Ccc_Adminhtml_Model_System_Config_Source_Vendor_Forgotpassword::FORGOTPASS_FLOW_IP
        );

        if (in_array($helper->getVendorForgotPasswordFlowSecure(), $checkForgotPasswordFlowTypes) && $remoteAddr) {
            $forgotPassword = $this->getCollection()
                ->addFieldToFilter('ip', array('eq' => $remoteAddr))
                ->addFieldToFilter(
                    'requested_date',
                    array('gt' => Mage::getModel('core/date')->date(null, '-1 hour'))
                );

            if ($forgotPassword->getSize() > $helper->getVendorForgotPasswordIpTimes()) {
                return false;
            }
        }
        return true;
    }
}
