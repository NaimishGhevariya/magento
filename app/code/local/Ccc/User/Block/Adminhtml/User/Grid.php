<?php

class Ccc_User_Block_Adminhtml_User_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('userGrid');
		$this->setDefaultSort('user_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	public function _prepareCollection()
	{
		$collection = Mage::getModel('ccc_user/data')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	public function _prepareColumns()
	{
		$this->addColumn('user_id', array(
			'header' => Mage::helper('user')->__('ID'),
			'align' => 'right',
			'width' => '50px',
			'index' => 'user_id',
		));
		$this->addColumn('first_name', array(
			'header' => Mage::helper('user')->__('First Name'),
			'align' => 'left',
			'width' => '120px',
			'index' => 'first_name',
		));
		$this->addColumn('last_name', array(
			'header' => Mage::helper('user')->__('Last Name'),
			'align' => 'left',
			'width' => '120px',
			'index' => 'last_name',
		));
		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}
