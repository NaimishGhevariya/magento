<?php

class Ccc_Hulk_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup
{
    public function getDefaultEntities()
    {
        $entityAttributes = array(
            Ccc_Hulk_Model_Resource_Hulk::ENTITY => array(
                'entity_model' => 'hulk/hulk',
                'attribute_model' => 'hulk/attribute',
                'table' => 'hulk/hulk',
                'attributes' => array(
                    'temp' => array(
                        'type' => 'varchar',
                        'label' => 'firstname',
                        'input' => 'text',
                        'global' => 0,
                        'visible' => true,
                        'required' => true,
                        'user_defined' => true,
                        'visible_on_front' => true
                    )
                ),
            )
        );

        return $entityAttributes;
    }
}
