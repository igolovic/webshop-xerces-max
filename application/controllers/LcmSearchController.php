<?php

class LcmSearchController extends Zend_Controller_Action
{
    protected $_cms;

    public function init() {
        
        Zend_Loader::loadClass('LCM_CMS');
		$this->_cms = new LCM_CMS;

        $this->_helper->viewRenderer('search-' . $this->_cms->getLanguage());
		$this->_helper->layout->setLayout('layout-' . $this->_cms->getLanguage());

        $co  = $this->_cms->getCartObject();
        $totals = $co->getCartTotals();
        $this->view->cartItemCount = $totals['cartItemCount'];
        $this->view->cartValueTotal = $totals['cartValueTotal'];
	
		$mo = $this->_cms->getMenuObject();
		$mo->fetchItems(array('parentId' => '1', 'active' => '1', 'orderBy' => 'm.order'));
		$this->view->menuItems  = $mo->getItems();
			
        $param = $this->getRequest()->getParam(1);
        $param = str_replace('lcm-sitemap/', '', $param);
	
		// handle printing task
		if($param && (preg_match('/^print=/', $param) == 1))
		{
			$this->_helper->layout->setLayout('layout-print');
			$param = str_replace('print=/', '', $param);
		}        
    }

    public function indexAction()
    {
        if($_POST['searchQuery'])
		{
            filter_input(INPUT_POST, $_POST['searchQuery'], FILTER_SANITIZE_STRING);
            $searchQuery = $_POST['searchQuery'];

            $searchQuery = trim($searchQuery);
            $searchQuery = preg_replace('/[ ]{2,20}/', ' ', $searchQuery);
            $searchWords = explode(' ', $searchQuery);

            $this->view->searchQuery = $searchQuery;
            
            $foundItems = array();
                        
            // search only current language articles
            $ao = $this->_cms->getArticleObject();
            $ao->fetchItems(array('search' => $searchWords, 'active' => '1'));
            $ai = $ao->getItems();

            foreach($ai as $item)
		    {
                $breadCrumb = $ao->getBreadCrumb($item->getId());
                $path = $breadCrumb[count($breadCrumb) - 1]['path'];
                
                $fi['groupTitle'] = $this->_cms->title2path($path);
                
                $fi['title'] = strip_tags($item->getTitle());
                $fi['description_text'] = strip_tags($item->getDescription()) . ' #BREAK# ' . strip_tags($item->getText());
                $fi['description_text'] = str_replace('#BREAK#', '<br /><br />', $fi['description_text']);
                
                $regex = implode('|', $searchWords);

                $countTitle = preg_match_all('/' . $regex . '/i', $fi['title'], $matches);
                $countDescription_text = preg_match_all('/' . $regex . '/i', $fi['description_text'], $matches);
                $fi['sort'] = $countTitle + $countDescription_text;
                
                if($fi['sort'] > 0)
				{
                    $fi['title'] = preg_replace('/(' . $regex . ')/i', '<span class="highlight">$0</span>', $fi['title']);
                    $fi['description_text'] = preg_replace('/(' . $regex . ')/i', '<span class="highlight">$0</span>', $fi['description_text']);
                    $foundItems[] = $fi;
                }
            }

            // search only current language article groups
            $ago = $this->_cms->getArticleGroupObject();
            $ago->fetchItems(array('search' => $searchWords, 'active' => '1'));
            $agi = $ago->getItems();
				
			foreach($agi as $item)
			{
                $breadCrumb = $ago->getBreadCrumb($item->getId());
                $path = $breadCrumb[count($breadCrumb) - 1]['path'];
                
                $fi['groupTitle'] = $this->_cms->title2path($path);
                
                $fi['title'] = strip_tags($item->getTitle());
                $fi['description_text'] = strip_tags($item->getDescription());
                
                $regex = implode('|', $searchWords);

                $countTitle = preg_match_all('/' . $regex . '/i', $fi['title'], $matches);
                $countDescription_text = preg_match_all('/' . $regex . '/i', $fi['description_text'], $matches);
                $fi['sort'] = $countTitle + $countDescription_text;
                
                if($fi['sort'] > 0)
				{
                    $fi['title'] = preg_replace('/(' . $regex . ')/i', '<span class="highlight">$0</span>', $fi['title']);
                    $fi['description_text'] = preg_replace('/(' . $regex . ')/i', '<span class="highlight">$0</span>', $fi['description_text']);
                    $foundItems[] = $fi;
                }
            }
            
            // search only current language products
            $po = $this->_cms->getProductObject();
            $po->fetchItems(array('search' => $searchWords, 'active' => '1'));
            $pi = $po->getItems();
				
			foreach($pi as $item)
			{
                $breadCrumb = $po->getBreadCrumb($item->getId());
                $path = $breadCrumb[count($breadCrumb) - 1]['path'];
                
                $fi['groupTitle'] = $this->_cms->title2path($path);
                
                $fi['title'] = strip_tags($item->getTitle());
                $fi['description_text'] = strip_tags($item->getDescription()) . ' #BREAK# ' . strip_tags($item->getText());
                $fi['description_text'] = str_replace('#BREAK#', '<br /><br />', $fi['description_text']);
                
                $regex = implode('|', $searchWords);

                $countTitle = preg_match_all('/' . $regex . '/i', $fi['title'], $matches);
                $countDescription_text = preg_match_all('/' . $regex . '/i', $fi['description_text'], $matches);
                $fi['sort'] = $countTitle + $countDescription_text;
                
                if($fi['sort'] > 0)
				{
                    $fi['title'] = preg_replace('/(' . $regex . ')/i', '<span class="highlight">$0</span>', $fi['title']);
                    $fi['description_text'] = preg_replace('/(' . $regex . ')/i', '<span class="highlight">$0</span>', $fi['description_text']);
                    $foundItems[] = $fi;
                }
            }

            // search only current language product groups
            $pgo = $this->_cms->getProductGroupObject();
            $pgo->fetchItems(array('search' => $searchWords, 'active' => '1'));
            $pgi = $pgo->getItems();
				
			foreach($pgi as $item)
			{
                $breadCrumb = $pgo->getBreadCrumb($item->getId());
                $path = $breadCrumb[count($breadCrumb) - 1]['path'];
                
                $fi['groupTitle'] = $this->_cms->title2path($path);                
                $fi['title'] = strip_tags($item->getTitle());
                $fi['description_text'] = strip_tags($item->getDescription());
                
                $regex = implode('|', $searchWords);
    
                $countTitle = preg_match_all('/' . $regex . '/i', $fi['title'], $matches);
                $countDescription_text = preg_match_all('/' . $regex . '/i', $fi['description_text'], $matches);
                $fi['sort'] = $countTitle + $countDescription_text;
                
                if($fi['sort'] > 0) {
    
                    $fi['title'] = preg_replace('/(' . $regex . ')/i', '<span class="highlight">$0</span>', $fi['title']);
                    $fi['description_text'] = preg_replace('/(' . $regex . ')/i', '<span class="highlight">$0</span>', $fi['description_text']);
                    $foundItems[] = $fi;
                }
            }

            $foundItems = $this->_cms->quicksort($foundItems);
            $this->view->foundItems = $foundItems;
        }
        else
		{
            $this->view->foundItems = array();
        }
    }
}

?>