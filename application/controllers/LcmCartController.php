<?php

class LcmCartController extends Zend_Controller_Action
{
    protected $_language;
    protected $_cms;
    
    public function init()
    {
        Zend_Loader::loadClass('LCM_CMS');
		$this->_cms = new LCM_CMS;
	
		$this->_language = $this->_cms->getLanguage();
		$this->_helper->layout->setLayout('layout-' . $this->_language);
	
		$mo = $this->_cms->getMenuObject();
		$mo->fetchItems(array('parentId' => '1', 'active' => '1', 'orderBy' => 'm.order'));
		$this->view->menuItems  = $mo->getItems();
	
		$param = $this->getRequest()->getParam(1);
		$param = str_replace('lcm-cart/', '', $param);
		
		// handle printing task
		if($param && (preg_match('/^print=/', $param) == 1)) {
			
			$this->_helper->layout->setLayout('layout-print');
			$param = str_replace('print=/', '', $param);
		}
        
        switch($param)
		{
            case 'add-to-cart':
                    if(isset($_POST['id']) && isset($_POST['quantity']))
					{
                        $id = $_POST['id'];
                        $quantity = $_POST['quantity'];
                        $cart = $this->_cms->getCartObject();
                        
                        if($quantity < 1)
						{    
                            $cartTotals = $cart->getCartTotals();
                        }
                        else
						{    
                            $cartTotals = $cart->addToCart($id, $quantity);
                        }

                        $data = array('cartItemCount' => $cartTotals['cartItemCount'],
                                      'cartValueTotal' => $cartTotals['cartValueTotal']);                        

                        $this->_helper->json($data);
                    }
                break;

            case 'remove-from-cart':
                    if(isset($_POST['id']))
					{                    
                        $id = $_POST['id'];
                        $cart = $this->_cms->getCartObject();
                        $cartTotals = $cart->removeFromCart($id);
                        
                        $data = array('cartItemCount' => $cartTotals['cartItemCount'],
                                      'cartValueTotal' => $cartTotals['cartValueTotal']);
                        
                        $this->_helper->json($data);
                    }
                break;

            case 'empty-cart':
                    $cart = $this->_cms->getCartObject();
                    $cartTotals = $cart->emptyCart();
                    
                    $data = array('cartItemCount' => 0,
                                  'cartValueTotal' => 0);
								  
                    $this->_helper->json($data);
                break;
            
            case 'index':
                    $co  = $this->_cms->getCartObject();
                    $totals = $co->getCartTotals();
                    $this->view->cartItemCount = $totals['cartItemCount'];
                    $this->view->cartValueTotal = $totals['cartValueTotal'];
                    
                    if($totals['cartValueTotal'] == 0)
					{   
                        $this->view->inpOrderVisibility = 'hidden';
                        $this->view->inpEmptyVisibility = 'hidden';
                    }
                    else
					{    
                        $this->view->inpOrderVisibility = 'visible';
                        $this->view->inpEmptyVisibility = 'visible';
                    }
                    
                    $this->view->cartItems = $co->getCartItems();
                    $this->_helper->viewRenderer('index-' . $this->_language);
                break;
        }
    }

    public function indexAction()
    {
    }
}

?>