<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('vendor/eav_attribute'), 'sort_order', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable'  => false,
        'length'    => 255,
        'comment'   => 'sort order'
    ));



// $installer = $this;
// $installer->getConnection()->getTable('vendor/eav_attribute')
//     ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
//         'unsigned' => true,
//         'nullable' => false,
//         'primary'  => true,
//     ), 'Attribute ID');


$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$setup->addAttribute(Ccc_Vendor_Model_Resource_Vendor::ENTITY, 'password_hash', array(
    'group'                      => 'General',
    'input'                      => 'text',
    'type'                       => 'int',
    'label'                      => 'password_hash',
    'frontend_class'             => 'validate-digits',
    'backend'                    => '',
    'visible'                    => 1,
    'required'                   => 0,
    'user_defined'               => 1,
    'searchable'                 => 1,
    'filterable'                 => 0,
    'comparable'                 => 1,
    'visible_on_front'           => 1,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front'   => 1,
    'global'                     => Ccc_Vendor_Model_Resource_Eav_Attribute::SCOPE_STORE,
));
