<?php

class Ccc_Vendor_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Query param name for last url visited
     */
    const REFERER_QUERY_PARAM_NAME = 'referer';

    /**
     * Route for vendor account login page
     */
    const ROUTE_ACCOUNT_LOGIN = 'vendor/account/login';

    /**
     * Config name for Redirect Vendor to Account Dashboard after Logging in setting
     */
    const VendorXML_PATH_VENDOR_STARTUP_REDIRECT_TO_DASHBOARD = 'vendor/startup/redirect_dashboard';

    /**
     * Config paths to VAT related vendor groups
     */
    const XML_PATH_VENDOR_VIV_INTRA_UNION_GROUP = 'vendor/create_account/viv_intra_union_group';
    const XML_PATH_VENDOR_VIV_DOMESTIC_GROUP = 'vendor/create_account/viv_domestic_group';
    const XML_PATH_VENDOR_VIV_INVALID_GROUP = 'vendor/create_account/viv_invalid_group';
    const XML_PATH_VENDOR_VIV_ERROR_GROUP = 'vendor/create_account/viv_error_group';

    /**
     * Config path to option that enables/disables automatic group assignment based on VAT
     */
    const XML_PATH_VENDOR_VIV_GROUP_AUTO_ASSIGN = 'vendor/create_account/viv_disable_auto_group_assign_default';

    /**
     * Config path to support email
     */
    const XML_PATH_SUPPORT_EMAIL = 'trans_email/ident_support/email';

    /**
     * Configuration path to expiration period of reset password link
     */
    const XML_PATH_VENDOR_RESET_PASSWORD_LINK_EXPIRATION_PERIOD
    = 'default/vendor/password/reset_link_expiration_period';

    /**
     * Configuration path to require admin password on vendor password change
     */
    const XML_PATH_VENDOR_REQUIRE_ADMIN_USER_TO_CHANGE_USER_PASSWORD
    = 'vendor/password/require_admin_user_to_change_user_password';

    /**
     * Config name for Redirect Customer to Account Dashboard after Logging in setting
     */
    const XML_PATH_VENDOR_STARTUP_REDIRECT_TO_DASHBOARD = 'vendor/startup/redirect_dashboard';

    /**
     * Configuration path to password forgotten flow change
     */
    const XML_PATH_VENDOR_FORGOT_PASSWORD_FLOW_SECURE = 'admin/security/forgot_password_flow_secure';
    const XML_PATH_VENDOR_FORGOT_PASSWORD_EMAIL_TIMES = 'admin/security/forgot_password_email_times';
    const XML_PATH_VENDOR_FORGOT_PASSWORD_IP_TIMES    = 'admin/security/forgot_password_ip_times';



    public function isLoggedIn()
    {
        return Mage::getSingleton('vendor/session')->isLoggedIn();
    }

    /**
     * Retrieve logged in vendor
     *
     * @return Ccc_Vendor_Model_Vendor
     */
    public function getVendor()
    {
        if (empty($this->_vendor)) {
            $this->_vendor = Mage::getSingleton('vendor/session')->getVendor();
        }
        return $this->_vendor;
    }

    /**
     * Retrieve vendor groups collection
     *
     * @return Ccc_Vendor_Model_Entity_Group_Collection
     */
    public function getGroups()
    {
        if (empty($this->_groups)) {
            $this->_groups = Mage::getModel('vendor/group')->getResourceCollection()
                ->setRealGroupsFilter()
                ->load();
        }
        return $this->_groups;
    }

    /**
     * Retrieve current (logged in) vendor object
     *
     * @return Ccc_Vendor_Model_Vendor
     */
    public function getCurrentVendor()
    {
        return $this->getVendor();
    }

    /**
     * Retrieve full vendor name from provided object
     *
     * @param Varien_Object $object
     * @return string   
     */
    public function getFullVendorName($object = null)
    {
        $name = '';
        if (is_null($object)) {
            $name = $this->getVendorName();
        } else {
            $config = Mage::getSingleton('eav/config');

            if (
                $config->getAttribute('vendor', 'prefix')->getIsVisible()
                && ($object->getPrefix()
                    || $object->getVendorPrefix())
            ) {
                $name .= ($object->getPrefix() ? $object->getPrefix() : $object->getVendorPrefix()) . ' ';
            }

            $name .= $object->getFirstname() ? $object->getFirstname() : $object->getVendorFirstname();

            if (
                $config->getAttribute('vendor', 'middlename')->getIsVisible()
                && ($object->getMiddlename()
                    || $object->getVendorMiddlename())
            ) {
                $name .= ' ' . ($object->getMiddlename()
                    ? $object->getMiddlename()
                    : $object->getVendorMiddlename());
            }

            $name .= ' ' . ($object->getLastname()
                ? $object->getLastname()
                : $object->getVendorLastname());

            if (
                $config->getAttribute('vendor', 'suffix')->getIsVisible()
                && ($object->getSuffix()
                    || $object->getVendorSuffix())
            ) {
                $name .= ' ' . ($object->getSuffix()
                    ? $object->getSuffix()
                    : $object->getVendorSuffix());
            }
        }
        return $name;
    }

    /**
     * Retrieve current vendor name
     *
     * @return string
     */
    public function getVendorName()
    {
        return $this->getVendor()->getName();
    }

    /**
     * Retrieve vendor login url
     *
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->_getUrl(self::ROUTE_ACCOUNT_LOGIN, $this->getLoginUrlParams());
    }

    /**
     * Retrieve parameters of vendor login url
     *
     * @return array
     */
    public function getLoginUrlParams()
    {
        $params = array();

        $referer = $this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME);

        if (
            !$referer && !Mage::getStoreConfigFlag(self::XML_PATH_VENDOR_STARTUP_REDIRECT_TO_DASHBOARD)
            && !Mage::getSingleton('vendor/session')->getNoReferer()
        ) {
            $referer = Mage::getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true));
            $referer = Mage::helper('core')->urlEncode($referer);
        }

        if ($referer) {
            $params = array(self::REFERER_QUERY_PARAM_NAME => $referer);
        }

        return $params;
    }

    /**
     * Retrieve vendor login POST URL
     *
     * @return string
     */
    public function getLoginPostUrl()
    {
        $params = array();
        if ($this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME)) {
            $params = array(
                self::REFERER_QUERY_PARAM_NAME => $this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME)
            );
        }
        return $this->_getUrl('vendor/account/loginPost', $params);
    }

    /**
     * Retrieve vendor logout url
     *
     * @return string
     */
    public function getLogoutUrl()
    {
        return $this->_getUrl('vendor/account/logout');
    }

    /**
     * Retrieve vendor dashboard url
     *
     * @return string
     */
    public function getDashboardUrl()
    {
        return $this->_getUrl('vendor/account');
    }

    /**
     * Retrieve vendor account page url
     *
     * @return string
     */
    public function getAccountUrl()
    {
        return $this->_getUrl('vendor/account');
    }

    /**
     * Retrieve vendor register form url
     *
     * @return string
     */
    public function getRegisterUrl()
    {
        return $this->_getUrl('vendor/account/create');
    }

    /**
     * Retrieve vendor register form post url
     *
     * @return string
     */
    public function getRegisterPostUrl()
    {
        return $this->_getUrl('vendor/account/createpost');
    }

    /**
     * Retrieve vendor account edit form url
     *
     * @return string
     */
    public function getEditUrl()
    {
        return $this->_getUrl('vendor/account/edit');
    }

    /**
     * Retrieve vendor edit POST URL
     *
     * @return string
     */
    public function getEditPostUrl()
    {
        return $this->_getUrl('vendor/account/editpost');
    }

    /**
     * Retrieve url of forgot password page
     *
     * @return string
     */
    public function getForgotPasswordUrl()
    {
        return $this->_getUrl('vendor/account/forgotpassword');
    }

    /**
     * Check is confirmation required
     *
     * @return bool
     */
    public function isConfirmationRequired()
    {
        return $this->getVendor()->isConfirmationRequired();
    }

    /**
     * Retrieve confirmation URL for Email
     *
     * @param string $email
     * @return string
     */
    public function getEmailConfirmationUrl($email = null)
    {
        return $this->_getUrl('vendor/account/confirmation', array('email' => $email));
    }

    /**
     * Check whether vendors registration is allowed
     *
     * @return bool
     */
    public function isRegistrationAllowed()
    {
        $result = new Varien_Object(array('is_allowed' => true));
        Mage::dispatchEvent('vendor_registration_is_allowed', array('result' => $result));
        return $result->getIsAllowed();
    }

    /**
     * Retrieve name prefix dropdown options
     *
     * @return array|bool
     */
    // public function getNamePrefixOptions($store = null)
    // {
    //     return $this->_prepareNamePrefixSuffixOptions(
    //         Mage::helper('vendor/address')->getConfig('prefix_options', $store)
    //     );
    // }

    /**
     * Retrieve name suffix dropdown options
     *
     * @return array|bool
     */
    // public function getNameSuffixOptions($store = null)
    // {
    //     return $this->_prepareNamePrefixSuffixOptions(
    //         Mage::helper('vendor/address')->getConfig('suffix_options', $store)
    //     );
    // }

    /**
     * Unserialize and clear name prefix or suffix options
     *
     * @param string $options
     * @return array|bool
     */
    protected function _prepareNamePrefixSuffixOptions($options)
    {
        $options = trim($options);
        if (empty($options)) {
            return false;
        }
        $result = array();
        $options = explode(';', $options);
        foreach ($options as $value) {
            $value = $this->escapeHtml(trim($value));
            $result[$value] = $value;
        }
        return $result;
    }

    /**
     * Generate unique token for reset password confirmation link
     *
     * @return string
     */
    public function generateResetPasswordLinkToken()
    {
        return Mage::helper('core')->uniqHash();
    }

    /**
     * Retrieve vendor reset password link expiration period in days
     *
     * @return int
     */
    public function getResetPasswordLinkExpirationPeriod()
    {
        return (int) Mage::getConfig()->getNode(self::XML_PATH_VENDOR_RESET_PASSWORD_LINK_EXPIRATION_PERIOD);
    }

    /**
     * Retrieve is require admin password on vendor password change
     *
     * @return bool
     */
    public function getIsRequireAdminUserToChangeUserPassword()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_VENDOR_REQUIRE_ADMIN_USER_TO_CHANGE_USER_PASSWORD);
    }

    /**
     * Get default vendor group id
     *
     * @param Mage_Core_Model_Store|string|int $store
     * @return int
     */
    // public function getDefaultVendorGroupId($store = null)
    // {
    //     return (int)Mage::getStoreConfig(Ccc_Vendor_Model_Group::XML_PATH_DEFAULT_ID, $store);
    // }

    /**
     * Retrieve forgot password flow secure type
     *
     * @return int
     */
    public function getVendorForgotPasswordFlowSecure()
    {
        return (int)Mage::getStoreConfig(self::XML_PATH_VENDOR_FORGOT_PASSWORD_FLOW_SECURE);
    }

    /**
     * Retrieve forgot password requests to times per 24 hours from 1 e-mail
     *
     * @return int
     */
    public function getVendorForgotPasswordEmailTimes()
    {
        return (int)Mage::getStoreConfig(self::XML_PATH_VENDOR_FORGOT_PASSWORD_EMAIL_TIMES);
    }

    /**
     * Retrieve forgot password requests to times per hour from 1 IP
     *
     * @return int
     */
    public function getVendorForgotPasswordIpTimes()
    {
        return (int)Mage::getStoreConfig(self::XML_PATH_VENDOR_FORGOT_PASSWORD_IP_TIMES);
    }
}
