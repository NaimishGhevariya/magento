<?php

$this->startSetup();

$this->addEntityType(Ccc_Hulk_Model_Resource_Hulk::ENTITY, [
    'entity_model'                => 'hulk/hulk',
    'attribute_model'             => 'hulk/attribute',
    'table'                       => 'hulk/hulk',
    'increment_per_store'         => '0',
    'additional_attribute_table'  => 'hulk/eav_attribute',
    'entity_attribute_collection' => 'hulk/hulk_attribute_collection',
]);

$this->createEntityTables('hulk');
$this->installEntities();

$default_attribute_set_id = Mage::getModel('eav/entity_setup', 'core_setup')
    ->getAttributeSetId('hulk', 'Default');

$this->run("UPDATE `eav_entity_type` SET `default_attribute_set_id` = {$default_attribute_set_id} WHERE `entity_type_code` = 'hulk'");

$this->endSetup();
