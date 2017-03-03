<?php
class Koban_MagentoForKoban_Model_Product {
	public function SaveProduct($prd) 
	{
		$res = new stdClass();
		$res->Label = $prd->name;
		$res->Reference = $prd->sku;
		$res->Obsolete = false;
		$res->Comments = $prd->description;
		
		$cat = new stdClass();
		$cat->Reference = $prd->category_ids[0];
		$res->Catproduct = $cat;
		$res->eShopURL = $prd->getProductUrl();
		$res->Ht = round($prd->price / 1.20, 2);
		$res->Ttc = $prd->price;
		$res->Vat = 20;
		$res->Margin = 0;
		$res->IsSelling = true;
		$res->Prht = $prd->price;
		//$res->Unit = '';
		$res->StockMin = 0;
		
		Mage::log($res, null, 'koban.log'); 
		Mage::helper('magentoforkoban/KobanAPI')->PostJSON('ncProduct/PostOne?uniqueproperty=Reference&catproductuniqueproperty=Reference', $res);
	}
	public function DeleteProduct($prd)
	{
		$res = new stdClass();
		$res->Label = $prd->name;
		$res->Reference = $prd->sku;
		$res->Obsolete = true;
		$res->Comments = $prd->description;
		
		$cat = new stdClass();
		$cat->Reference = $prd->category_ids[0];
		$res->Catproduct = $cat;
		$res->eShopURL = $prd->getProductUrl();
		$res->Ht = round($prd->price / 1.20, 2);
		$res->Ttc = $prd->price;
		$res->Vat = 20;
		$res->IsSelling = true;
		$res->Margin = 0;
		$res->Prht = $prd->price;
		//$res->Unit = '';
		$res->StockMin = 0;
		
		Mage::helper('magentoforkoban/KobanAPI')->PostJSON('ncProduct/PostOne?uniqueproperty=Reference&catproductuniqueproperty=Reference', $res);
	}
}
?>