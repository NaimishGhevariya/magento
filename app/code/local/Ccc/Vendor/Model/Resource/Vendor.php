<?php
class Ccc_Vendor_Model_Resource_Vendor extends Mage_Eav_Model_Entity_Abstract
{

	const ENTITY = 'vendor';

	public function __construct()
	{

		$this->setType(self::ENTITY)
			->setConnection('core_read', 'core_write');

		parent::__construct();
	}

	public function loadByEmail(Ccc_Vendor_Model_Vendor $vendor, $email, $testOnly = false)
	{
		$adapter = $this->_getReadAdapter();
		$bind    = array('vendor_email' => $email);

		$select  = $adapter->select()
			->from($this->getEntityTable() . "_varchar", array($this->getEntityIdField()))
			->where('value = :vendor_email');

		// if ($vendor->getSharingConfig()->isWebsiteScope()) {
		// 	if (!$vendor->hasData('website_id')) {
		// 		Mage::throwException(
		// 			Mage::helper('vendor')->__('Vendor website ID must be specified when using the website scope')
		// 		);
		// 	}
		// $bind['website_id'] = (int)$vendor->getWebsiteId();
		// $select->where('website_id = :website_id');
		// }

		$vendorId = $adapter->fetchOne($select, $bind);

		if ($vendorId) {
			$this->load($vendor, $vendorId);
		} else {
			$vendor->setData(array());
		}

		return $this;
	}

	/**
	 * Change vendor password
	 *
	 * @param Mage_Vendor_Model_Vendor $vendor
	 * @param string $newPassword
	 * @return Mage_Vendor_Model_Resource_Vendor
	 */
	public function changePassword(Ccc_Vendor_Model_Vendor $vendor, $newPassword)
	{
		$vendor->setPassword($newPassword);
		$this->saveAttribute($vendor, 'password_hash');
		return $this;
	}

	/**
	 * Check whether there are email duplicates of vendors in global scope
	 *
	 * @return bool
	 */
	public function findEmailDuplicates()
	{
		$adapter = $this->_getReadAdapter();
		$select  = $adapter->select()
			->from($this->getTable('vendor/entity'), array('email', 'cnt' => 'COUNT(*)'))
			->group('email')
			->order('cnt DESC')
			->limit(1);
		$lookup = $adapter->fetchRow($select);
		if (empty($lookup)) {
			return false;
		}
		return $lookup['cnt'] > 1;
	}

	/**
	 * Check vendor by id
	 *
	 * @param int $vendorId
	 * @return bool
	 */
	public function checkVendorId($vendorId)
	{
		$adapter = $this->_getReadAdapter();
		$bind    = array('entity_id' => (int)$vendorId);

		$select  = $adapter->select()
			->from($this->getTable('vendor/vendor'), 'entity_id')
			->where('entity_id = :entity_id')
			->limit(1);
		$result = $adapter->fetchOne($select, $bind);
		if ($result) {
			return true;
		}
		return false;
	}
}
