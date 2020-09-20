<?php

class LcmSendlinkController extends Zend_Controller_Action
{
    protected $_co;
    protected $_cms;

    public function init()
    {
        Zend_Loader::loadClass('LCM_CMS');
        $this->_cms = new LCM_CMS;
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer('captcha-' . $this->_cms->getLanguage());
        
		$param = $this->getRequest()->getParam(1);
        $param = str_replace('lcm-sendlink/', '', $param);
	
		// handle printing task
		if($param && (preg_match('/^link=/', $param) == 1))
		{    
			$param = str_replace('link=', '', $param);
		}
	
		$this->view->link = $param;				
	}

    public function indexAction()
    {
        $this->_captcha = new Zend_Captcha_Image(array('font' => realpath('lcm-dir-captcha') . '/Arial.ttf',
					    								'wordLen' => 4,
													    'dotNoiseLevel' => 20,
													    'lineNoiseLevel' => 2));

        if(isset($_POST['recipientAddress']))
		{
            if(strlen(trim($_POST['recipientAddress'])) > 0)
			{
				if(isset($_POST['senderName']) && strlen(trim($_POST['senderName'])) > 0)
				{
					if(strlen(trim($_POST['recipientAddress'])) > 0)
					{
						if(isset($_POST['captcha']))
						{
							if($this->_captcha->isValid($_POST['captcha']))
							{
								$content = file_get_contents(realpath('lcm-dir-sendlink') . '/sendlink-message-' . $this->_cms->getLanguage() . '.html');
								$content = str_replace('#NAME#', $_POST['senderName'], $content);
								$content = str_replace('#LINK#', $_POST['sentLink'], $content);
								
								$headers  = 'MIME-Version: 1.0' . "\r\n";
								$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
								
								if(mail(trim($_POST['recipientAddress']),
								   			$this->_cms->getSendlinkSubject(),
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
				}
				else
				{
					$this->view->senderNameError = true;
				}
			}
			else
			{
				$this->view->recipientAddressError = true;
			}
		}
        
		$this->_captcha->setImgDir('./lcm-dir-captcha/images');
		$this->_captcha->setImgUrl('/lcm-dir-captcha/images');
	
		$this->view->captchaMD5 = $this->_captcha->generate();
		$this->view->captchaImage = $this->_captcha->render();
	}
}

?>