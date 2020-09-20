<?php

class LcmContactController extends Zend_Controller_Action
{
    public function init()
    {
        Zend_Loader::loadClass('LCM_CMS');
		$this->_cms = new LCM_CMS;

		$this->_language = $this->_cms->getLanguage();
		$this->_helper->layout->setLayout('layout-' . $this->_language);

        $co  = $this->_cms->getCartObject();
        $totals = $co->getCartTotals();
        $this->view->cartItemCount = $totals['cartItemCount'];
        $this->view->cartValueTotal = $totals['cartValueTotal'];
        
		$mo = $this->_cms->getMenuObject();
		$mo->fetchItems(array('parentId' => '1', 'active' => '1', 'orderBy' => 'm.order'));
		$this->view->menuItems  = $mo->getItems();

        // make the LCM API available in view
        $this->view->cms = $this->_cms;

		$path = $this->getRequest()->getParam(1);
	
		// handle printing task
		if($path && (preg_match('/^print=/', $path) == 1))
		{    
			$this->_helper->layout->setLayout('layout-print');
			$path = preg_replace('/^print=/', '', $path);
		}
	
        $this->_helper->viewRenderer('contact-' . $this->_language);    
    }

    public function indexAction()
    {
        $this->_captcha = new Zend_Captcha_Image(array('font' => realpath('lcm-dir-captcha') . '/Arial.ttf',
													    'wordLen' => 4,
													    'dotNoiseLevel' => 20,
													    'lineNoiseLevel' => 2));

        if(isset($_POST['senderAddress']))
		{
            if(strlen(trim($_POST['senderAddress'])) > 0)
	    	{
                if(isset($_POST['senderName']) && strlen(trim($_POST['senderName'])) > 0)
				{
                    if(isset($_POST['message']) && strlen($_POST['message']) > 0 && strlen($_POST['message']) < 4000)
		    		{
                        if(isset($_POST['captcha']))
						{
                            if ($this->_captcha->isValid($_POST['captcha']))
			    			{    
                                $content = file_get_contents(realpath('lcm-dir-contact/message.html'));
                                $content = str_replace('#NAME#', trim($_POST['senderName']), $content);
                                $content = str_replace('#EMAIL#', trim($_POST['senderAddress']), $content);   
                                $content = str_replace('#MESSAGE#', trim($_POST['message']), $content);
                                
                                $headers  = 'MIME-Version: 1.0' . "\r\n";
                                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                                
                                if(mail(trim($this->_cms->getContactEmailRecipient()),
                                		     'Nova kontakt poruka - ' . strftime('%y-%m-%d', time()),
                                        	 $content,
                                        	 $headers))
								{
                                    $this->view->ok =  true;
                                }
                                else
								{
                                    $this->view->sendMailError =  true;
                                }
                            }
                            else
			    			{
                                $this->view->captchaError =  true;
                            }
                        }
                    }
                    else
		    		{
                        $this->view->messageError = true;                        
                    }
                }
                else
				{                    
                    $this->view->senderNameError = true;
                }
            }
            else
	    	{
                $this->view->senderAddressError = true;
            }
        }
        
        $this->_captcha->setImgDir('./lcm-dir-captcha/images');
        $this->_captcha->setImgUrl('./lcm-dir-captcha/images');
        
       	$this->view->captchaMD5 = $this->_captcha->generate();
		$this->view->captchaImage = $this->_captcha->render();        
    }
}

?>