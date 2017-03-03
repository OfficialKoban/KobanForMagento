<?php
class Koban_MagentoForKoban_Block_Tracking extends Mage_Page_Block_Html_Head
{ 
	// Ajoute le script global Koban
	public function getCssJsHtml()
    {
		$html = parent::getCssJsHtml();
		$url = "http://track.itsonlyleads.com/libapi/kobantracker.js";
		$isssl = Mage::getStoreConfig('tracking/api/isssl',Mage::app()->getStore());
		if ($isssl == "1")
			$url = "https://addin-koban.com:444/libapi/kobantracker-s.js";
		$script = "<script type='text/javascript'>(function (i, s, o, g, r, a, m) {
    i['KobanObject'] = r; i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date(); a = s.createElement(o),
            m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
})
(window, document, 'script', '". $url ."', 'kb');";
		
		$api = Mage::getStoreConfig('tracking/api/api',Mage::app()->getStore());
		$script .= "kb('reg', '" . $api . "');</script>";
		
		$html .= $script;
		return $html;
	}
}
?>