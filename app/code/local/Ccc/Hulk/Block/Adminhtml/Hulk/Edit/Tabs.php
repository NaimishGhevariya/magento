<?php

class Ccc_Hulk_Block_Adminhtml_Hulk_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{


    public function __construct()
    {
        parent::__construct();
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('hulk')->__('Hulk Information'));
    }
    public function getHulk()
    {
        return Mage::registry('current_hulk');
    }

    protected function _beforeToHtml()
    {

        $hulkAttributes = Mage::getResourceModel('hulk/hulk_attribute_collection');

        if (!$this->getHulk()->getId()) {
            foreach ($hulkAttributes as $attribute) {
                $default = $attribute->getDefaultValue();
                if ($default != '') {
                    $this->getHulk()->setData($attribute->getAttributeCode(), $default);
                }
            }
        }

        $attributeSetId = $this->getHulk()->getResource()->getEntityType()->getDefaultAttributeSetId();



        // $attributeSetId = 21;

        $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
            ->setAttributeSetFilter($attributeSetId)
            ->setSortOrder()
            ->load();

        $defaultGroupId = 0;
        foreach ($groupCollection as $group) {
            if ($defaultGroupId == 0 or $group->getIsDefault()) {
                $defaultGroupId = $group->getId();
            }
        }


        foreach ($groupCollection as $group) {
            $attributes = array();
            foreach ($hulkAttributes as $attribute) {
                if ($this->getHulk()->checkInGroup($attribute->getId(), $attributeSetId, $group->getId())) {
                    $attributes[] = $attribute;
                }
            }

            if (!$attributes) {
                continue;
            }

            $active = $defaultGroupId == $group->getId();
            $block = $this->getLayout()->createBlock('hulk/adminhtml_hulk_edit_tab_attributes')
                ->setGroup($group)
                ->setAttributes($attributes)
                ->setAddHiddenFields($active)
                ->toHtml();
            $this->addTab('group_' . $group->getId(), array(
                'label'     => Mage::helper('hulk')->__($group->getAttributeGroupName()),
                'content'   => $block,
                'active'    => $active
            ));
        }
        return parent::_beforeToHtml();
    }
}
