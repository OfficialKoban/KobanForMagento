<?php
class Koban_MagentoForKoban_Model_Order {
	public function OrderDone($ord) 
	{
		$res = $this->GetAPIObjectFromOrder($ord);
		$apires = Mage::helper('magentoforkoban/KobanAPI')->PostJSON('ncOrder/PostOneFromeShop?uniqueproperty=Extcode&thirduniqueproperty=Email', $res);
		Mage::log($apires, null, 'koban.log');
		return $apires->Result;
	}
	public function OrderCancelled($ord)
	{
		Mage::log('canc', null, 'koban.log'); 
		
	}
	public function GetAPIObjectFromOrder($ord)
	{
		$res = new stdClass();
		$cust = new stdClass();
		$cust->Label = $ord->customer_lastname;
		$cust->FirstName = $ord->customer_firstname;
		$cust->EMail = $ord->customer_email;
		$tp = new stdClass();
		$tp->Code = "PART";
		$cust->Type = $tp;
		$billingadrs = $ord->getBillingAddress();
		$shippingadrs = $ord->getShippingAddress();
		
		$badrs = new stdClass();
		$badrs->Street = str_replace("\n", ',', $billingadrs->street);
		Mage::log($ord->debug(), null, 'koban.log');
		$badrs->City = $billingadrs->city;
		$badrs->ZipCode = $billingadrs->postcode;
		$badrs->Country = $billingadrs->country_id;
		$badrs->Name = $billingadrs->lastname;
		$badrs->FirstName = $billingadrs->firstname;
		$badrs->Phone = $billingadrs->telephone;
		$cust->Address = $badrs;
		
		$cmd = new stdClass();
		$cmd->Extcode = $ord->increment_id;
		$cmd->Status = "PENDING";
		$cmd->Dateorder = $ord->created_at;
		$sadrs = new stdClass();
		$sadrs->Street = str_replace("\n", ',', $shippingadrs->street);
		$sadrs->City = $shippingadrs->city;
		$sadrs->ZipCode = $shippingadrs->postcode;
		$sadrs->Country = $shippingadrs->country_id;
		$sadrs->Name = $shippingadrs->lastname;
		$sadrs->FirstName = $shippingadrs->firstname;
		$sadrs->Phone = $shippingadrs->telephone;
		$cmd->ShippingAddress = $sadrs;
		$cmd->Comments = $ord->customer_note;
		
		$ship = new stdClass();
		$ship->Amount = $ord->shipping_amount;
		$ship->TaxAmount = $ord->shipping_tax_amount;
		$ship->Reference = "PORT";
		
		$cmd->Shipping = $ship;
		$cmd->CurrencyCode = $ord->order_currency_code;
		$lines = Array();
		foreach ($ord->getAllItems() as $item) {
			$line = new stdClass();
			$prd = new stdClass();
			
			$prd->Reference = $item->getProduct()->sku;
			$line->Product = $prd;
			$line->Quantity = $item->qty_ordered;
			$line->Prht = $item->base_price;
			$line->UnitPrice = $item->base_price;
			$line->Margin = 0;
			$line->Red = $item->discount_amount;
			$line->Ht = $item->row_total;
			$line->Vat = $item->tax_percent;
			$line->Ttc = $item->row_total_incl_tax;
			array_push($lines, $line);
		}
		$cmd->Lines = $lines;
		
		$res->Order = $cmd;
		$res->Customer = $cust;
		$cook = Mage::helper('magentoforkoban/KobanTrack')->CookieVar();
		$cook->IP = $ord->remote_ip;
		$res->Track = $cook;
		
		Mage::log($res, null, 'koban.log');
		return $res;
	}
}
?>