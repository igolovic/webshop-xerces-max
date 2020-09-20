<?php

class LcmProductGroupController extends Zend_Controller_Action
{
    protected $_productTitle;
    protected $_productGroupTitle;
    protected $_language;
    protected $_cms;
    
    private function renderSingleItem()
    {
		$po = $this->_cms->getProductObject();
		$po->fetchItems(array('title' => str_replace('-', ' ', $this->_productTitle), 'active' => '1'));
		$pi = $po->getItems();
	
		// product with given title exists
		if(count($pi) > 0)
		{
	
			// product breadcrumb
			$breadCrumb = $po->getBreadCrumb($pi[0]->getId());
			
			foreach($breadCrumb as $bci)
			{			
				$bci['path'] = $this->_cms->title2path($bci['path']);
				$temp[] = $bci;
			}
			
			$this->view->breadCrumb = $temp;
	
			// product details
			$this->view->id = $pi[0]->getId();
			$this->view->title = $pi[0]->getTitle();
			$this->view->text = $pi[0]->getText();
			$this->view->price = $pi[0]->getPrice();
			
			$this->view->imageList = $pi[0]->getImages();
			$this->view->imageLList = $pi[0]->getImagesL();
			$this->view->showCart = $pi[0]->getShowCart();
			$idl = $pi[0]->getImageDescriptions();
			
			if(isset($idl))
			{
				foreach($idl as $item)
				{
					$idl2[] = str_replace(array("'", "\""), "\'", $item);
				}
			}
			
			$this->view->imageDescriptionList = isset($idl2) ? $idl2 : array();

			$this->view->extraInfoList = $pi[0]->getExtraInfoItems();
			
			$lpnl = array();
			$lpil = $pi[0]->getLinkedProductIds();
							
			if(isset($lpil))
			{
					
				foreach($lpil as $item)
				{
					$po->fetchItems(array('id' => $item, 'active' => '1'));
					$pi = $po->getItems();
					$lpnl[] = $pi[0]->getTitle();
				}
			}
			
			$this->view->linkedProductNameList = $lpnl;
			$this->_helper->viewRenderer('product-details-' . $this->_language);
			
			return true;
		}
		return false;
    }
    
    public function renderGroup()
    {    
		$pgo = $this->_cms->getProductGroupObject();
		$pgo->fetchItems(array('title' => str_replace('-', ' ', $this->_productGroupTitle), 'active' => '1'));
		$pgi = $pgo->getItems();
	
		// group with given title exists
		if(count($pgi) > 0)
		{
			$po = $this->_cms->getProductObject();
			$po->fetchItems(array('productGroupTitle' => str_replace('-', ' ', $this->_productGroupTitle), 'active' => '1', 'custom' => "pt.title <> ''", 'orderBy' => 'p.order'));
			$pi = $po->getItems();
	
			// product items exist
			if(count($pi) > 0)
			{
				// only one product item exists
				if(count($pi) == 1)
				{    
					$this->_productTitle = $pi[0]->getTitle();
					$this->renderSingleItem();
				}
				// multiple product items exists
				else
				{
					// group breadcrumb
					$breadCrumb = $pgo->getBreadCrumb($pgi[0]->getId());
					
					foreach($breadCrumb as $bci)
					{
						$bci['path'] = $this->_cms->title2path($bci['path']);
						$temp[] = $bci;
					}
					
					$this->view->breadCrumb = $temp;
		
					// current group information
					$this->view->groupItem = $pgi[0];
		
					$this->view->productItems = $pi;
					$this->_helper->viewRenderer('product-index-' . $this->_language);
				}
			
				return true;
			}
			// product items don't exist, check if subgroups exist
			else
			{
				// group breadcrumb
				$breadCrumb = $pgo->getBreadCrumb($pgi[0]->getId());
			
				foreach($breadCrumb as $bci)
				{    
					$bci['path'] = $this->_cms->title2path($bci['path']);
					$temp[] = $bci;
				}
				
				$this->view->breadCrumb = $temp;
		
				// current group information
				$this->view->groupItem = $pgi[0];

				// child group items
				$pgo->setItems(array());
				$pgo->fetchItems(array('parentId' => $pgi[0]->getId()));
				$pgi = $pgo->getItems();

				// subgroups exist
				if(isset($pgi) && count($pgi) > 0)
				{
					$this->view->groupItems = $pgi;
				}
				else
				{
					$this->view->groupItems = array();
				}
		
				$this->_helper->viewRenderer('group-index-' . $this->_language);
			}
			
			return true;
		}
		
		return false;
    }
    
    public function init() 
	{
		Zend_Loader::loadClass('Lcm_CMS');
		$this->_cms = new Lcm_CMS;
	
		$this->_language = $this->_cms->getLanguage();
		$this->_helper->layout->setLayout('layout-' . $this->_language);
		$this->view->maxThumbHeight = $this->_cms->getMaxThumbHeight();	
	
		$co  = $this->_cms->getCartObject();
		$totals = $co->getCartTotals();
		$this->view->cartItemCount = $totals['cartItemCount'];
		$this->view->cartValueTotal = $totals['cartValueTotal'];
	
		$mo = $this->_cms->getMenuObject();
		$mo->fetchItems(array('parentId' => '1', 'active' => '1', 'orderBy' => 'm.order'));
		$this->view->menuItems  = $mo->getItems();
	
		// make the Lcm API available in view
		$this->view->cms = $this->_cms;	
		$path = $this->getRequest()->getParam(1);

		// handle printing task
		if($path && (preg_match('/^print=/', $path) == 1))
		{    
			$this->_helper->layout->setLayout('layout-print');
			$path = preg_replace('/^print=/', '', $path);
		}
	   
		$segments = explode('/', $path);
		$segmentCount = count($segments);

		// presume parameter format: group-title/.../group-title/product-title
		$this->_productTitle = $segments[$segmentCount - 1];
		
		if(!$this->renderSingleItem())
		{        
			// if product with given title can't render i.e. doesn't exist, try rendering group with given title
			// parameter format: group-title
			//				  group-title/.../group-title/group-title
			$this->_productGroupTitle = $segments[$segmentCount - 1];
	
			if(!$this->renderGroup())
			{
				$this->_helper->viewRenderer('empty-' . $this->_language);
			}
		}
    }

    public function indexAction()
    {
    }
}

?>