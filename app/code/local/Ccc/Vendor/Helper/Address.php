
<?php
class Ccc_Vendor_Helper_Address extends Mage_Core_Helper_Abstract
{
    public function getAttributeValidationClass($attributeCode)
    {
        /** @var $attribute Mage_Vendor_Model_Attribute */
        $attribute = isset($this->_attributes[$attributeCode]) ? $this->_attributes[$attributeCode]
            : Mage::getSingleton('eav/config')->getAttribute('vendor_address', $attributeCode);
        $class = $attribute ? $attribute->getFrontend()->getClass() : '';

        if (in_array($attributeCode, array('firstname', 'middlename', 'lastname', 'prefix', 'suffix', 'taxvat'))) {
            if ($class && !$attribute->getIsVisible()) {
                $class = ''; // address attribute is not visible thus its validation rules are not applied
            }

            /** @var $vendorAttribute Mage_Vendor_Model_Attribute */
            $vendorAttribute = Mage::getSingleton('eav/config')->getAttribute('vendor', $attributeCode);
            $class .= $vendorAttribute && $vendorAttribute->getIsVisible()
                ? $vendorAttribute->getFrontend()->getClass() : '';
            $class = implode(' ', array_unique(array_filter(explode(' ', $class))));
        }

        return $class;
    }
}
