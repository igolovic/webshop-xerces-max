<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected $_cms;
    
    protected function _initGeneral()
    {
      // load CMS here so that the routes can be configured in time
      Zend_Loader::loadClass('LCM_CMS');
      $this->_cms = new LCM_CMS;
      
      Zend_Session::start(true);
    }
}

?>