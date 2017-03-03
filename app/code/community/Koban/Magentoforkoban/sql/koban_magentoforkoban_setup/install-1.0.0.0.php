<?php
$installer = $this;
 
$installer->startSetup();
 
$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'example_field', array(
    'group'             => 'General',
    'type'              => 'text',
    'backend'           => '',
    'input_renderer'    => 'test/catalog_product_helper_form_example',//definition of renderer
    'label'             => 'Example field',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => 'simple,configurable,bundle,grouped',
    'is_configurable'   => false,
));
 
$installer->endSetup();