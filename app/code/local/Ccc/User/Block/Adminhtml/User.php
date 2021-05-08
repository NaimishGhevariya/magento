<?php

class Ccc_User_Block_Adminhtml_User extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_user';	//FileName
		$this->_blockGroup = 'user';			//ModuleName
		$this->_headerText = Mage::helper('user')->__('User Manager');
		$this->_addButtonLabel = Mage::helper('user')->__('Add User');
		parent::__construct();
	}
}
