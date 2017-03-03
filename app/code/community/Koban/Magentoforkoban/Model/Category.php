<?php
class Koban_MagentoForKoban_Model_Category {
	public function SaveCategory($cat) 
	{
		$res = new stdClass();
		$res->Label = $cat->name;
		$res->Reference = $cat->entity_id;
		$res->Obsolete = ($cat->is_active != 1);
		$par = new stdClass();
		$par->Reference = $cat->parent_id;
		$res->ParentCategory = $par;
		$res->eShopURL = $cat->getUrl();
		Mage::log($res, null, 'koban.log'); 
		Mage::helper('magentoforkoban/KobanAPI')->PostJSON('ncCatproduct/PostOne?uniqueproperty=Reference', $res);
	}
	public function DeleteCategory($cat)
	{
		$res = new stdClass();
		$res->Label = $cat->name;
		$res->Reference = $cat->entity_id;
		$res->Obsolete = true;
		$par = new stdClass();
		$par->Reference = $cat->parent_id;
		$res->ParentCategory = $par;
		$res->eShopURL = $cat->getUrl();
		Mage::log($res, null, 'koban.log'); 
		Mage::helper('magentoforkoban/KobanAPI')->PostJSON('ncCatproduct/PostOne?uniqueproperty=Reference', $res);
	}
}
?>