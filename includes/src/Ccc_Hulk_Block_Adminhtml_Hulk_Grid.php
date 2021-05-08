<?php

class Ccc_Hulk_Block_Adminhtml_Hulk_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('hulkGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('hulk_filter');
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();

        $collection = Mage::getModel('hulk/hulk')->getCollection()
            ->addAttributeToSelect('firstname')
            ->addAttributeToSelect('lastname')
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('phoneNo');

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $collection->joinAttribute(
            'firstname',
            'hulk/firstname',
            'entity_id',
            null,
            'inner',
            $adminStore
        );

        $collection->joinAttribute(
            'lastname',
            'hulk/lastname',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'email',
            'hulk/email',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'phoneNo',
            'hulk/phoneNo',
            'entity_id',
            null,
            'inner',
            $adminStore
        );

        $collection->joinAttribute(
            'id',
            'hulk/entity_id',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            array(
                'header' => Mage::helper('hulk')->__('id'),
                'width'  => '50px',
                'index'  => 'id',
            )
        );
        $this->addColumn(
            'firstname',
            array(
                'header' => Mage::helper('hulk')->__('First Name'),
                'width'  => '50px',
                'index'  => 'firstname',
            )
        );

        $this->addColumn(
            'lastname',
            array(
                'header' => Mage::helper('hulk')->__('Last Name'),
                'width'  => '50px',
                'index'  => 'lastname',
            )
        );

        $this->addColumn(
            'email',
            array(
                'header' => Mage::helper('hulk')->__('Email'),
                'width'  => '50px',
                'index'  => 'email',
            )
        );

        $this->addColumn(
            'phoneNo',
            array(
                'header' => Mage::helper('hulk')->__('Phone Number'),
                'width'  => '50px',
                'index'  => 'phoneNo',
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'   => Mage::helper('hulk')->__('Action'),
                'width'    => '50px',
                'type'     => 'action',
                'getter'   => 'getId',
                'actions'  => array(
                    array(
                        'caption' => Mage::helper('hulk')->__('Delete'),
                        'url'     => array(
                            'base' => '*/*/delete',
                        ),
                        'field'   => 'id',
                    ),
                ),
                'filter'   => false,
                'sortable' => false,
            )
        );

        parent::_prepareColumns();
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            array(
                'store' => $this->getRequest()->getParam('store'),
                'id'    => $row->getId()
            )
        );
    }
}
