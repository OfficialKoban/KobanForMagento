<?php
class Koban_MagentoForKoban_Model_Customer {
	public function SaveCustomer($cus) 
	{
		$res = new stdClass();
		$res->Label = $cus->lastname;
		$res->Firstname = $cus->firstname;
		$res->EMail = $cus->email;
		$tp = new stdClass();
		$tp->Code = "PART";
		$res->Type = $tp;
		$billingadrs = Mage::getModel('customer/address')->load($cus->getDefaultBilling());
		$shippingadrs = Mage::getModel('customer/address')->load($cus->getDefaultShipping());
		
		$badrs = new stdClass();
		$badrs->Reference = $billingadrs->entity_id;
		$badrs->Street = str_replace("\n", ',', $billingadrs->street);
		$badrs->City = $billingadrs->city;
		$badrs->ZipCode = $billingadrs->postcode;
		$badrs->Country = $billingadrs->country_id;
		$badrs->Name = $billingadrs->lastname;
		$badrs->FirstName = $billingadrs->firstname;
		$badrs->Phone = $billingadrs->telephone;
		$res->Address = $badrs;
		
		$sadrs = new stdClass();
		$sadrs->Reference = $shippingadrs->entity_id;
		$sadrs->Street = str_replace("\n", ',', $billingadrs->street);
		$sadrs->City = $shippingadrs->city;
		$sadrs->ZipCode = $shippingadrs->postcode;
		$sadrs->Country = $shippingadrs->country_id;
		$sadrs->Name = $shippingadrs->lastname;
		$sadrs->FirstName = $shippingadrs->firstname;
		$sadrs->Phone = $shippingadrs->telephone;
		$res->DelivAddress = $sadrs;
		
		Mage::log($res, null, 'koban.log'); 
		Mage::helper('magentoforkoban/KobanAPI')->PostJSON('ncThird/PostOneFromEMail', $res);
	}
}
?>