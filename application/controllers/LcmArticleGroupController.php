<?php

class LcmArticleGroupController extends Zend_Controller_Action
{
    protected $_language;
    protected $_articleTitle;
    protected $_articleGroupTitle;
    protected $_cms;

    private function renderSingleItem()
    {
		$ao = $this->_cms->getArticleObject();
		$ao->fetchItems(array('title' => str_replace('-', ' ', $this->_articleTitle), 'active' => '1'));
		$ai = $ao->getItems();
	
		// article with given title exists
		if(count($ai) > 0)
		{
			// article breadcrumb
			$breadCrumb = $ao->getBreadCrumb($ai[0]->getId());
	
			foreach($breadCrumb as $bci)
			{
				$bci['path'] = $this->_cms->title2path($bci['path']);
				$temp[] = $bci;
			}
	
			$this->view->breadCrumb = $temp;
	
			// article details
			$publishDate = $ai[0]->getPublishDate();
	
			if($publishDate != '0000-00-00 00:00:00')
			{
				$this->view->publishDate = $publishDate;
			}
			else
			{
				$this->view->publishDate = false;
			}
	
			$this->view->title = $ai[0]->getTitle();
			$this->view->text = $ai[0]->getText();
			$this->view->imageList = $ai[0]->getImages();
			$this->view->imageLList = $ai[0]->getImagesL();
			$idl = $ai[0]->getImageDescriptions();
	
			if(isset($idl))
			{
				foreach($idl as $item)
				{
					$idl2[] = str_replace(array("'", "\""), "\'", $item);
				 }
			}
				
			$this->view->imageDescriptionList = isset($idl2) ? $idl2 : array();
	
			$lanl = array();
			$lail = $ai[0]->getLinkedArticleIds();

			if(isset($lail))
			{
				foreach($lail as $item)
				{                
						$ao->fetchItems(array('id' => $item, 'active' => '1'));
						$ai = $ao->getItems();
						$lanl[] = $ai[0]->getTitle();
				 }
			 }
			
			 $this->view->linkedArticleNameList = $lanl;	    	    
			 $this->_helper->viewRenderer('article-details-' . $this->_language);
			
			 return true;
		}
		
		return false;
    }

    private function renderGroup()
    {
		$ago = $this->_cms->getArticleGroupObject();
		$ago->fetchItems(array('title' => str_replace('-', ' ', $this->_articleGroupTitle), 'active' => '1'));
		$agi = $ago->getItems();
	
		// group with given title exists
		if(count($agi) > 0)
		{
			$ao = $this->_cms->getArticleObject();
			$ao->fetchItems(array('articleGroupTitle' => str_replace('-', ' ', $this->_articleGroupTitle), 'active' => '1', 'custom' => "at.title <> ''"));
			$ai = $ao->getItems();
	
			// article items exist
			if(count($ai) > 0)
			{
			// only one article item exists
			if(count($ai) == 1)
			{
				$this->_articleTitle = $ai[0]->getTitle();
				$this->renderSingleItem();
			}
			// multiple article items exists
			else
			{
				// group breadcrumb
				$breadCrumb = $ago->getBreadCrumb($agi[0]->getId());
	
				foreach($breadCrumb as $bci)
				{
					$bci['path'] = $this->_cms->title2path($bci['path']);
					$temp[] = $bci;
				}
	
				$this->view->breadCrumb = $temp;
	
				// current group information
				$this->view->groupItem = $agi[0];
				
				// count of article introductions to be displayed with image, title and description in a list
				$this->view->longIntroCount = $this->_cms->getLongIntroCount();
				
				$this->view->articleItems = $ai;
				$this->_helper->viewRenderer('article-index-' . $this->_language);
			}
		}
		// article items don't exist, check if subgroups exist
		else
		{
			// group breadcrumb
			$breadCrumb = $ago->getBreadCrumb($agi[0]->getId());
	
			foreach($breadCrumb as $bci)
			{
				$bci['path'] = $this->_cms->title2path($bci['path']);
				$temp[] = $bci;
			}
	
			$this->view->breadCrumb = $temp;
	
			// current group information
			$this->view->groupItem = $agi[0];
	
			// child group items
			$ago->setItems(array());
			$ago->fetchItems(array('parentId' => $agi[0]->getId()));
	
			$agi = $ago->getItems();
			
			// subgroups exist
			if(count($agi) > 0)
			{
				$this->view->groupItems = $agi;
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
        Zend_Loader::loadClass('LCM_CMS');
		$this->_cms = new LCM_CMS;
		
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
	
		// make the LCM API available in view
		$this->view->cms = $this->_cms;
	
		$path = $this->getRequest()->getParam(1);
	
		// handle printing task
		if($path && (preg_match('/^print=/', $path) == 1))
		{
			$this->_helper->layout->setLayout('layout-print');
			$path = preg_replace('/^print=/', '', $path);
		}
			
		// parameters don't exist
		if(!$path)
		{
			$home = $this->_cms->getHomeDefault();
			$this->_articleGroupTitle = $home;
	
			if(!$this->renderGroup())
			{
				$this->_articleTitle = $home;
	
				if(!$this->renderSingleItem())
				{
					$this->_helper->viewRenderer('empty-' . $this->_language);
				}
			}
		}
		// parameters exist
		else
		{
			$segments = explode('/', $path);
			$segmentCount = count($segments);
	
			// presume parameter format: group-title/.../group-title/article-title
			$this->_articleTitle = $segments[$segmentCount - 1];
	
			if(!$this->renderSingleItem())
			{
				// if article with given title can't render i.e. doesn't exist, try rendering group with given title
				// parameter format: group-title
				//	             group-title/.../group-title/group-title
				$this->_articleGroupTitle = $segments[$segmentCount - 1];	
				
				if(!$this->renderGroup())
				{
					$this->_helper->viewRenderer('empty-' . $this->_language);
				}
			}
		}
    }

    public function indexAction()
    {
    }
}

?>