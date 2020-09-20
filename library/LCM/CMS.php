<?php

class LCM_CMS
{
    protected $_database;
    protected $_menu;
    protected $_articleGroup;
    protected $_article;
    protected $_productGroup;
    protected $_product;
    protected $_cart;
    protected $_user;
    protected $_order;
    protected $_language;
	protected $_contactEmailRecipient;
    
    public function __construct()
    {
		// set local settings for Croatia
		setlocale(LC_ALL, "croatian");
	
		// set LCM configuration which is appended in Zend's configuration file
		$config = new Zend_Config_Ini('../application/configs/application.ini', 'production');
		Zend_Registry::set('config', $config);
	
		// classes used in session must be loaded before session_start() gets called
		Zend_Loader::loadClass('LCM_Product');
		Zend_Loader::loadClass('LCM_Cart');
	
		$router  = Zend_Controller_Front::getInstance()->getRouter();
		$router->addConfig($config, 'routes');
    }

    public function title2path($title)
    {
		return strtolower(str_replace(array(' ', 'š', 'đ', 'č', 'ć', 'ž', 'Š', 'Đ', 'Č', 'Ć', 'Ž'),
						  array('-', 's', 'd', 'c', 'c', 'z', 'S', 'D', 'C', 'C', 'Z'),
						  $title));
    }

    public function quicksort($array)
    {
		if(count($array) < 2)
		{
			return $array;
		}
	
        $left = $right = array();
        reset($array);
        $pivot_key = key($array);
        $pivot = array_shift($array);

        foreach($array as $k => $v)
		{
				if($v['sort'] > $pivot['sort'])
				{
					$left[$k] = $v;
				}
				else
				{
					$right[$k] = $v;
				}
			}
	
			return array_merge($this->quicksort($left), array($pivot_key => $pivot), $this->quicksort($right));
		}

	public function getContactEmailRecipient()
	{
		$config = Zend_Registry::get('config');
		return $config->LCM->contactEmailRecipient;		
	}

    public function getMaxThumbHeight()
    {
		$config = Zend_Registry::get('config');
		return $config->LCM->maxThumbHeight;
    }

    public function getSendlinkSubject()
    {
		$config = Zend_Registry::get('config');
		$sendlinkSubjects = $config->LCM->language->sendlink->subject->toArray();
		return $sendlinkSubjects[$this->getLanguage() - 1];
    }
    
    public function getHomeDefault()
    {
		$config = Zend_Registry::get('config');
		$homeUrls = $config->LCM->language->home->toArray();
		return $homeUrls[$this->getLanguage() - 1];
    }
    
    public function getSearchContextSize()
    {
		$config = Zend_Registry::get('config');
        return $config->LCM->searchContextSize;
    }

    public function getDocsPath()
    {
		$config = Zend_Registry::get('config');
        return $config->LCM->docsPath;
    }

    public function getLanguage()
    {
		if(isset($_SESSION['languageId']))
		{
			return $_SESSION['languageId'];
		}
		else
		{
			$config = Zend_Registry::get('config');
			return $config->LCM->language->preset;
		}
    }

    public function setLanguage($languageId)
    {
		$_SESSION['languageId'] = $languageId;
	}
       
    public function getMenuObject()
    {
    	if(!$this->_menu)
		{
			Zend_Loader::loadClass('LCM_Menu');
			$this->_menu = new LCM_Menu($this->getDatabaseObject(), $this->getLanguage());
		}
	
		return $this->_menu;
    }

    public function getArticleGroupObject()
    {
		if(!$this->_articleGroup)
		{
			Zend_Loader::loadClass('LCM_ArticleGroup');
			$this->_articleGroup = new LCM_ArticleGroup($this->getDatabaseObject(), $this->getLanguage());
		}
	
		return $this->_articleGroup;
    }

    public function getArticleObject()
    {
    	if(!$this->_article)
		{
			Zend_Loader::loadClass('LCM_Article');
			$this->_article = new LCM_Article($this->getDatabaseObject(), $this->getLanguage(), $this->getDocsPath());
		}
	
		return $this->_article;
    }

    public function getProductGroupObject()
    {
		if(!$this->_productGroup)
		{
			Zend_Loader::loadClass('LCM_ProductGroup');
			$this->_productGroup = new LCM_ProductGroup($this->getDatabaseObject(), $this->getLanguage());
		}
		
		return $this->_productGroup;
    }

    public function getProductObject()
    {
		if(!$this->_product)
		{
			Zend_Loader::loadClass('LCM_Product');
			$this->_product = new LCM_Product($this->getDatabaseObject(), $this->getLanguage(), $this->getDocsPath());
		}
	
		return $this->_product;
    }
    
    public function getCartObject()
    {
		if(!$this->_cart)
		{
			Zend_Loader::loadClass('LCM_Cart');
			$this->_cart = new LCM_Cart($this->getDatabaseObject(), $this->getLanguage());
		}
	
		return $this->_cart;
    }

    public function getUserObject()
    {
		if(!$this->_user)
		{
			Zend_Loader::loadClass('LCM_User');
			$this->_user = new LCM_User($this->getDatabaseObject(), $this->getLanguage());
		}
	
		return $this->_user;
    }

    public function getOrderObject()
    {
		if(!$this->_order)
		{
			Zend_Loader::loadClass('LCM_Order');
			$this->_order = new LCM_Order($this->getDatabaseObject(), $this->getLanguage());
		}
	
		return $this->_order;
    }
        
    public function getLongIntroCount()
    {
		$config = Zend_Registry::get('config');
		return $config->LCM->longIntroCount;
	}
    
    public function getLanguageObject()
    {
		if(!$this->_language)
		{
			Zend_Loader::loadClass('LCM_Language');
			$this->_language = new LCM_Language($this->getDatabaseObject(), $this->getLanguage(), $this);
		}
		return $this->_language;
    }
    
    public function getDatabaseObject()
    {
		if(!$this->_database)
		{
			Zend_Loader::loadClass('LCM_Database');
			$this->_database = new LCM_Database;
		}
		
		return $this->_database;
    }
}

?>