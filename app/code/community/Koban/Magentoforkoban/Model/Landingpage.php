<?php

class Koban_MagentoForKoban_Model_Landingpage
{
    public function toOptionArray()
    {
		$lps = Mage::helper('magentoforkoban/KobanAPI')->Get("ncLandingPage/GetAll");
		$res = array();
		array_push($res, array(
			'value' => '',
			'label' => ''
		));
		foreach ($lps as $k=>$v){
			$val = $v->Guid;
			$lb = $v->Label;
			$lpa = array(
				'value' => $val,
				'label' => $lb
			);
			array_push($res, $lpa);
		}
		return $res;
    }
}
?>