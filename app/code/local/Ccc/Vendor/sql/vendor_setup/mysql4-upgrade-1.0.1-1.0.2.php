<?php
$this->startSetup();

$this->addEntityType(Ccc_Vendor_Model_Resource_Product::ENTITY, [
    'entity_model'                => 'vendor/vendor_product',
    'attribute_model'             => 'vendor/productAttribute',
    'table'                       => 'vendor/product',
    'increment_per_store'         => '0',
    'additional_attribute_table'  => 'vendor/eav_productAttribute',
    'entity_attribute_collection' => 'vendor/product_attribute_collection',
]);

$this->createEntityTables('vendor_product');
$this->installEntities();

$default_attribute_set_id = Mage::getModel('eav/entity_setup', 'core_setup')
    ->getAttributeSetId('vendor', 'Default');

$this->run("UPDATE `eav_entity_type` SET `default_attribute_set_id` = {$default_attribute_set_id} WHERE `entity_type_code` = 'vendor_product'");

$this->endSetup();