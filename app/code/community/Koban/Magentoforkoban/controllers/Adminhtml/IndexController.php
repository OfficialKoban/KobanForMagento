 <?php

class Koban_Magentoforkoban_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action 
{
    protected function _initOrder()
    {
        $id = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($id);

        if (!$order->getId()) {
            $this->_getSession()->addError($this->__('This order no longer exists.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        Mage::register('sales_order', $order);
        Mage::register('current_order', $order);
        return $order;
    }

	public function omtkAction()
	{
		if ($order = $this->_initOrder()) {
			try {
				$kobanguid = Mage::getModel('magentoforkoban/order')->OrderDone($order);
				$sv = Mage::getStoreConfig('tracking/api/kobanserver',Mage::app()->getStore()).'/'.$path;
				$sv = str_replace('/api/v1', '', $sv);
				$this->getResponse()->setRedirect($sv.'/Order/Build/'.$kobanguid);
			}
			catch (Mage_Core_Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
			catch (Exception $e) {
				$this->_getSession()->addError($this->__('The order state has not been changed.'));
				Mage::logException($e);
			}
		}

	}
}
?>