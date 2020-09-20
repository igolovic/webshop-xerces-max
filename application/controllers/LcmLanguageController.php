<?php

class LcmLanguageController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        Zend_Loader::loadClass('LCM_CMS');
	    $this->_cms = new LCM_CMS;
        session_start();
        
	    $languageId = $this->getRequest()->getParam('languageId');
        $this->_cms->setLanguage($languageId);

        if(isset($_GET['returnUrl']))
        {
            $originalUrl = $_GET['returnUrl'];
            $translatedUrl = $this->_cms->getLanguageObject()->translateUrl($languageId, $originalUrl);
            header('Location: ' . $translatedUrl);
        }
        else
            header('Location: /');
    }
}

?>