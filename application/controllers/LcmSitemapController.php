<?php

class LcmSitemapController extends Zend_Controller_Action
{
    protected $_language;
    protected $_cms;

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
		$this->view->menuItems = $mo->getItems();
        
        $param = $this->getRequest()->getParam(1);
        $param = str_replace('lcm-sitemap', '', $param);

		// handle printing task
		if($param && (preg_match('/^print=/', $param) == 1))
		{
			$this->_helper->layout->setLayout('layout-print');
			$param = str_replace('print=/', '', $param);
		}
        
		$this->_helper->viewRenderer('sitemap-' . $this->_language);
    }

    public function indexAction()
    {     
    }
}

?>