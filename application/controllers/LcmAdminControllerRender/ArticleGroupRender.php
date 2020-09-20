<?php
	    if(isset($_POST['languageId']))
	    {
			$this->_cms->setLanguage($_POST['languageId']);
	    }

	    $this->view->selectedLanguageId = $this->_cms->getLanguage();
	    $ago = $this->_cms->getArticleGroupObject();

	    // fill the languages
	    $lo = $this->_cms->getLanguageObject();
	    $lo->fetchItems(array());
	    $li = $lo->getItems();
	    
	    // set the language combo box
	    $this->view->languageItems = $li;

	    $this->_helper->viewRenderer('admin-article-group');

	    // presume disabled parent group selection (update of an existing group or viewing an existing group)
	    $this->view->parentSelectEnabled = false;

	    // ID not defined - possible insert
	    preg_match('/lcm-admin-article-group$/', $param, $match);

	    if(isset($match[0]) && strlen($match[0]) > 0)
	    {
	        $this->view->parentSelectEnabled = true;

			// insert
			if($this->getRequest()->isPost() && isset($_POST['save']))
			{
				$lastInsertId = $ago->save(array(	'parentId' => $_POST['parentId'],
													'active' => isset($_POST['active']) ? '1' : '0',
													'languageId' => $this->view->selectedLanguageId,
													'title' => str_replace("'", "&#039;", $_POST['title']),
													'description' => str_replace("'", "&#039;", $_POST['description'])));
				
				$this->_helper->redirector($lastInsertId, 'lcm-admin-article-group');
			}
	
			$this->view->orderDisabled = 'true';
		}
            
	    // ID is defined
	    preg_match('/(?<=lcm-admin-article-group\/)[^delete]{1,6}/', $param, $match);
        
	    if(isset($match[0]) && strlen($match[0]) > 0)
	    {
			// update
			if($this->getRequest()->isPost() && isset($_POST['save']))
			{
				if(isset($_POST['articleGroupId']))
				{
					$ago->save(array('articleGroupId' => $_POST['articleGroupId'],
									 'languageId' => $this->view->selectedLanguageId,
									 'title' => str_replace("'", "&#039;", $_POST['title']),
									 'description' => str_replace("'", "&#039;", $_POST['description'])));
				}
			}
			
			// get group with given ID
			$ago->fetchItems(array('id' => $match[0], 'languageId' => $this->view->selectedLanguageId));
			$agi = $ago->getItems();
			
			$this->view->currentArticle_GroupId = $agi[0]->getId();
			$this->view->currentArticle_Title = $agi[0]->getTitle();
			$this->view->currentArticle_Description = $agi[0]->getDescription();
			$this->view->currentArticle_Active = $agi[0]->getActive() == '1' ? "checked='checked'" : "";
			$this->view->orderDisabled = 'false';
				
			// set selected group's order inside combo box
			$this->view->selectedOrder = $agi[0]->getOrder();
			
			// set selected group's title
			$this->view->selectedTitle = $agi[0]->getTitle();
					
			// get group's parent group
			$ago->fetchItems(array('id' => $agi[0]->getParentId()));
			$agParent = $ago->getItems();
					
			// set selected group's parent group title inside combo box
			if(isset($agParent[0]))
			{
				$this->view->selectedParentTitle = $agParent[0]->getTitle();
			}
			else
			{
				$this->view->selectedParentTitle = '';
			}
		}

	    // ID is defined and delete is specified
        preg_match('/(?<=lcm-admin-article-group\/)delete\/.+/', $param, $match);
            
	    if(isset($match[0]) && strlen($match[0]) > 0)
	    {   
			// handle delete
			$articleGroupId = trim($match[0], 'delete/');
			
			if($ago->delete($articleGroupId))
			{
				$this->_helper->redirector('', 'lcm-admin-article-group');	
			}
			else
			{
				$this->view->hasMessage = true;
				$this->view->isErr = true;
				$this->view->message = 'Greška kod brisanja. Prvo obrišite članke unutar grupe i podgrupe vezane za ovu grupu.';
			}
		}

        // fill the left menu
	    $ago->fetchItems(array('orderBy' => 'ag.parentId asc, ag.order asc'));
        $agi = $ago->getItems();

	    // set the left menu groups
		$this->view->articleGroupItems = $agi;
		$this->view->classDisabled = 'disabled';
?>