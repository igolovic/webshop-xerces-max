<?php

class LcmAdminController extends Zend_Controller_Action
{
    protected $_language;
    protected $_cms;

    public function init()
    {
        Zend_Loader::loadClass('LCM_CMS');
        $this->_cms = new LCM_CMS;
  
		$param = $this->getRequest()->getParam(1);

		if(preg_match('/lcm-admin-logout/', $param) > 0)
		{
			unset($_SESSION['userIsAdmin']);
			return header('Location: /lcm-admin-login');
		}

	  	if(isset($_SESSION['userIsAdmin']))
		{
			$this->_helper->layout->setLayout('layout-admin');
		
			if(preg_match('/lcm-admin-article-group/', $param) > 0)
			{            
				include_once('../application/controllers/LcmAdminControllerRender/ArticleGroupRender.php');
			}
			elseif(preg_match('/lcm-admin-article$/', $param) > 0)
			{    
				include_once('../application/controllers/LcmAdminControllerRender/ArticleRender.php');
			}
			elseif (preg_match('/lcm-admin-product-group/', $param) > 0)
			{
				include_once('../application/controllers/LcmAdminControllerRender/ProductGroupRender.php');
			}
			elseif (preg_match('/lcm-admin-product$/', $param) > 0)
			{
				include_once('../application/controllers/LcmAdminControllerRender/ProductRender.php');
			}
			elseif (preg_match('/lcm-admin-order/', $param) > 0)
			{
				include_once('../application/controllers/LcmAdminControllerRender/OrderRender.php');
			}
		}
		else
		{
			if(preg_match('/lcm-admin-login/', $param) == 0)
			{
				header('Location: /lcm-admin-login');
			}	
			else
			{
				if(isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password']))
				{					
					$user = $this->_cms->getUserObject();
					
					$username = $this->_cms->getDatabaseObject()->getDatabase()->quote($_POST['username']);
					$password = $this->_cms->getDatabaseObject()->getDatabase()->quote($_POST['password']);
					
					if($user->isUserAdmin($username, $password))
					{
						$_SESSION['userIsAdmin'] = '1';
						$this->_helper->layout->setLayout('layout-admin');
					}
					else
					{
						$this->_helper->layout->setLayout('layout-admin-login');						
					}
				}
				else
				{
					$this->_helper->layout->setLayout('layout-admin-login');
				}
			}
		}
    }

    public function indexAction()
    {
    }
}

?>