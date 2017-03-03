<?php
class Koban_MagentoForKoban_Model_Observer {
	public function subscribedToNewsletter(Varien_Event_Observer $observer) 
	{
		Mage::log('Début évènement souscription newsletter :', null, 'koban.log');
		$event = $observer->getEvent();
		$subscriber = $event->getDataObject();
		$data = $subscriber->getData();

		$statusChange = $subscriber->getIsStatusChanged();

		// Inscription à la newsletter
		if ($data['subscriber_status'] == "1" && $statusChange == true) {
			Mage::log('Inscription newsletter', null, 'koban.log');
			$res = array();
			$email = $subscriber->getEmail();
			$res["contact_email"] = $email;
			$lp = Mage::getStoreConfig('tracking/forms/nllp',Mage::app()->getStore());
			Mage::helper('magentoforkoban/KobanTrack')->PostTrack($lp, $res);
			Mage::log('Inscription newsletter OK', null, 'koban.log');
		}
		if ($data['subscriber_status'] == "3" && $statusChange == true) {
			Mage::log('Désinscription newsletter', null, 'koban.log');
			$res = array();
			$email = $subscriber->getEmail();
			$res["email"] = $email;
			Mage::helper('magentoforkoban/KobanAPI')->Post('ncEMailing/Unsubscribe', $res);
			Mage::log('Désinscription newsletter OK', null, 'koban.log');
		}
		return $observer;
	}
	
	public function CategorySaved(Varien_Event_Observer $observer)
	{
		Mage::log('Début évènement Modification catégorie', null, 'koban.log');
		$data = $observer->getEvent()->getCategory();
		Mage::getModel('magentoforkoban/category')->SaveCategory($data);
		Mage::log('Fin évènement Modification catégorie', null, 'koban.log');
	}
	public function CategoryDeleted(Varien_Event_Observer $observer)
	{
		Mage::log('Début évènement Suppression catégorie', null, 'koban.log');
		$data = $observer->getEvent()->getCategory();
		Mage::getModel('magentoforkoban/category')->DeleteCategory($data);
		Mage::log('Fin évènement Suppression catégorie', null, 'koban.log');
	}
	public function ProductSaved(Varien_Event_Observer $observer)
	{
		Mage::log('Début évènement Modification Produit', null, 'koban.log');
		$data = $observer->getEvent()->getProduct();
		Mage::getModel('magentoforkoban/product')->SaveProduct($data);
	}
	public function ProductDeleted(Varien_Event_Observer $observer)
	{
		Mage::log('Début évènement Suppression Produit', null, 'koban.log');
		$data = $observer->getEvent()->getProduct();
		Mage::getModel('magentoforkoban/product')->DeleteProduct($data);
		Mage::log('Fin évènement Suppression Produit', null, 'koban.log');
	}
	public function OrderDone(Varien_Event_Observer $observer)
	{
		Mage::log('Début évènement Commande effectuée', null, 'koban.log');
		$data = $observer->getEvent()->getOrder();
		Mage::getModel('magentoforkoban/order')->OrderDone($data);
		Mage::log('Fin évènement Commande effectuée', null, 'koban.log');
	}
	public function OrderCancelled(Varien_Event_Observer $observer)
	{
		Mage::log('Début évènement Commande annulée', null, 'koban.log');
		$data = $observer->getEvent()->getOrder();
		Mage::log($data->debug(), null, "koban.log");
		Mage::getModel('magentoforkoban/order')->OrderCancelled($data);
		Mage::log('Fin évènement Commande annulée', null, 'koban.log');
	}
	public function addOrderAction($observer)
    {
		$block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View) {
            $message = Mage::helper('sales')->__('Are you sure you want to Change Status?');

            $block->addButton('rto', 
                array( 'label' => Mage::helper('sales')->__('Koban'), 
                    'onclick' => "setLocation('{$block->getUrl('magentoforkoban/adminhtml_index/omtk')}')", 'class' => 'go' ));
        }
    }
}
?>