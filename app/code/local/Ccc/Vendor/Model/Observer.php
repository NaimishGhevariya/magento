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
 * Vendor module observer
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Ccc_Vendor_Model_Observer
{

    /**
     * Before load layout event handler
     *
     * @param Varien_Event_Observer $observer
     */
    public function beforeLoadLayout($observer)
    {
        $loggedIn = Mage::getSingleton('vendor/session')->isLoggedIn();

        $observer->getEvent()->getLayout()->getUpdate()
            ->addHandle('vendor_logged_' . ($loggedIn ? 'in' : 'out'));
    }


    /**
     * Revert emulated vendor group_id
     *
     * @param Varien_Event_Observer $observer
     */
    public function quoteSubmitAfter($observer)
    {
        /** @var $vendor Ccc_Vendor_Model_Vendor */
        $vendor = $observer->getQuote()->getVendor();
        if (!$vendor->getId()) {
            return;
        }

        $vendor->setGroupId(
            $vendor->getOrigData('group_id')
        );
        $vendor->save();
    }

    /**
     * Clear vendor flow password table
     *
     */
    public function deleteVendorFlowPassword()
    {
        $connection = Mage::getSingleton('core/resource')->getConnection('write');
        $condition  = array('requested_date < ?' => Mage::getModel('core/date')->date(null, '-1 day'));
        $connection->delete($connection->getTableName('vendor_flowpassword'), $condition);
    }
}