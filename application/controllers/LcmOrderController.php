<?php

class LcmOrderController extends Zend_Controller_Action
{
    protected $_language;
    protected $_cms;
    protected $_cartItemCount;

    public function init()
    {
        Zend_Loader::loadClass('LCM_CMS');
		$this->_cms = new LCM_CMS;

		$this->_language = $this->_cms->getLanguage();
		$this->_helper->layout->setLayout('layout-' . $this->_language);
	
		$mo = $this->_cms->getMenuObject();
		$mo->fetchItems(array('parentId' => '1', 'active' => '1', 'orderBy' => 'm.order'));
		$this->view->menuItems  = $mo->getItems();
        
        $co = $this->_cms->getCartObject();
        
        $this->view->cartItems = $co->getCartItems();
        
        $totals = $co->getCartTotals();
        $this->view->cartItemCount = $this->_cartItemCount = $totals['cartItemCount'];
        $this->view->cartValueTotal = $totals['cartValueTotal'];
	
        $param = $this->getRequest()->getParam(1);
        $param = str_replace('lcm-order/', '', $param);
        
		// handle printing task
		if($param && (preg_match('/^print=/', $param) == 1))
		{
			$this->_helper->layout->setLayout('layout-print');
			$param = str_replace('print=/', '', $param);
		}
        
        if($this->_cartItemCount > 0)
		{
            switch($param)
	    	{
                case 'new-order':
                        $this->_helper->viewRenderer->setNoRender();
                        $this->_helper->layout->disableLayout();
                        $this->newOrder();
                    break;
                 
				 case 'index':
                        $oo = $this->_cms->getOrderObject();
                        $this->view->paymentMethods = $oo->fetchPaymentMethods();
                        
                        if(isset($_POST['inpExistingCustomerId']) && strlen(trim($_POST['inpExistingCustomerId'])) > 0)
						{
                            $this->view->id = $_POST['inpExistingCustomerId'];
                            
                            $uo = $this->_cms->getUserObject();
                            $uo->fetchItems(array('id' => $_POST['inpExistingCustomerId']));
                            $ui = $uo->getItems();
                            
                            if(isset($ui[0]))
			    			{
                                $this->view->firstName = $ui[0]->getFirstName();
                                $this->view->lastName = $ui[0]->getLastName();
                                $this->view->company = $ui[0]->getCompany();                            
                                $this->view->address = $ui[0]->getAddress();
                                $this->view->city = $ui[0]->getCity();
                                $this->view->postalCode = $ui[0]->getPostalCode();
                                $this->view->state = $ui[0]->getState();
                                $this->view->phone = $ui[0]->getPhone();
                                $this->view->fax = $ui[0]->getFax();
                                $this->view->email = $ui[0]->getEmail();
                            }
                        }
                        
                        $this->_helper->viewRenderer('index-' . $this->_language);
                    break;
            }
        }
        else
		{
            $this->_helper->viewRenderer('no-data-' . $this->_language);
            return;
        }
    }

    private function newOrder()
    {  
        if($this->getRequest()->isXmlHttpRequest())
		{
            $status = '0';
            $orderId = $clientId = '0';
            $data = array();
            
            if(isset($_POST['firstname']) && strlen(trim($_POST['firstname'])) > 0 &&
               isset($_POST['lastname']) && strlen(trim($_POST['lastname'])) > 0 &&
               isset($_POST['address']) && strlen(trim($_POST['address'])) > 0 &&
               isset($_POST['city']) && strlen(trim($_POST['city'])) > 0 &&
               isset($_POST['postalcode']) && strlen(trim($_POST['postalcode'])) > 0 &&
               isset($_POST['state']) && strlen(trim($_POST['state'])) > 0 &&
               isset($_POST['phone']) && strlen(trim($_POST['phone'])) > 0 &&
               isset($_POST['email']) && strlen(trim($_POST['email'])) > 0 && 
               isset($_POST['paymentmethodid']))
		    {
                $uo = $this->_cms->getUserObject();
                if($clientId = $uo->save(array(
                        'firstname' => $_POST['firstname'],
                        'lastname' => $_POST['lastname'],
                        'company' => isset($_POST['company']) ? $_POST['company'] : '',
                        'address' => $_POST['address'],
                        'city' => $_POST['city'],
                        'postalcode' => $_POST['postalcode'],
                        'state' => $_POST['state'],
                        'phone' => $_POST['phone'],
                        'fax' => isset($_POST['fax']) ? $_POST['fax'] : '',
                        'email' => $_POST['email'])))
					{
							$oo = $this->_cms->getOrderObject();
							if($orderId = $oo->save(array(
									  'clientid' => $clientId,
									  'paymentmethodtypeid' => $_POST['paymentmethodid'])))
							{
								$co = $this->_cms->getCartObject();
								$cartItems = $co->getCartItems();
								
								foreach($cartItems as $cartItem)
								{
									$oo->saveRow(array('orderid' => $orderId,
													   'productid' => $cartItem->getId(),
													   'quantity' => $cartItem->getQuantity()));
								}
								
                    	    	$status = true;
                    		}
                		}
            		}
            
            	$data = array('status' => $status,
							  'orderId' => $orderId,
							  'clientId' => $clientId);
			  
            	$this->_helper->json($data);
        }
    }

    public function indexAction()
    {
    }
}

?>