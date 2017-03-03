<?php
class Koban_Magentoforkoban_Block_Synccatalog extends Mage_Adminhtml_Block_System_Config_Form_Field
{ 
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $url = $this->getUrl('catalog/product'); //

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('scalable')
                    ->setLabel('Lancer la synchronisation')
                    ->setOnClick("alert('prout')")
                    ->toHtml();
		$html = "<".$html;
        return $html;
    }
}
?>