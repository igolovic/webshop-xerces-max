<?php
	    $oo = $this->_cms->getOrderObject();
	    
	    if(isset($_POST['deleteOrder']) && isset($_POST['inpOrderId']))
		{
			$oo->deleteOrder($_POST['inpOrderId']);
		}
		
		$this->view->orderItems = $oo->fetchAllOrders();
		$this->view->orderItemsCount = count($this->view->orderItems);
		
		$this->_helper->viewRenderer('admin-order' . $this->_language);
?>