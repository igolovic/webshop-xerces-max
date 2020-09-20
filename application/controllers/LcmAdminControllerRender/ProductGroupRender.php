<?php
	    if(isset($_POST['languageId']))
	    {
			$this->_cms->setLanguage($_POST['languageId']);
        }
        
        $this->view->selectedLanguageId = $this->_cms->getLanguage();
        $pgo = $this->_cms->getProductGroupObject();
	    
	    // fill the languages
		$lo = $this->_cms->getLanguageObject();
		$lo->fetchItems(array());
		$li = $lo->getItems();
            
		// set the language combo box
		$this->view->languageItems = $li;

		$this->_helper->viewRenderer('admin-product-group');

	    // presume disabled parent group selection (update of an existing group or viewing an existing group)
	    $this->view->parentSelectEnabled = false;

		// ID not defined - possible insert
		preg_match('/lcm-admin-product-group$/', $param, $match);
            
	    if(isset($match[0]) && strlen($match[0]) > 0)
	    {
			$this->view->parentSelectEnabled = true;

			// insert
			if($this->getRequest()->isPost() && isset($_POST['save']))
			{
				$lastInsertId = $pgo->save(array('parentId' => $_POST['parentId'],
												 'active' => isset($_POST['active']) ? '1' : '0',
												 'languageId' => $this->view->selectedLanguageId,
												 'title' => str_replace("'", "&#039;", $_POST['title']),
												 'description' => str_replace("'", "&#039;", $_POST['description'])));
				
				$this->_helper->redirector($lastInsertId, 'lcm-admin-product-group');
			}
	
			$this->view->orderDisabled = 'true';
		}
            
	    // ID is defined
        preg_match('/(?<=lcm-admin-product-group\/)[^delete]{1,6}/', $param, $match);
            			
	    if(isset($match[0]) && strlen($match[0]) > 0)
	    {
			// update
			if($this->getRequest()->isPost() && isset($_POST['save']))
			{
				if(isset($_POST['productGroupId']))
				{
					$pgo->save(array('productGroupId' => $_POST['productGroupId'],
									 'languageId' => $this->view->selectedLanguageId,
									 'title' => str_replace("'", "&#039;", $_POST['title']),
									 'description' => str_replace("'", "&#039;", $_POST['description'])));
				}
			}
			
			// get group with given ID
			$pgo->fetchItems(array('id' => $match[0], 'languageId' => $this->view->selectedLanguageId));
			$pgi = $pgo->getItems();
					
			$this->view->currentProduct_GroupId = $pgi[0]->getId();
			$this->view->currentProduct_Title = $pgi[0]->getTitle();
			$this->view->currentProduct_Description = $pgi[0]->getDescription();
			$this->view->currentProduct_Active = $pgi[0]->getActive() == '1' ? "checked='checked'" : "";
			$this->view->orderDisabled = 'false';
							
			// set selected group's order inside combo box
            $this->view->selectedOrder = $pgi[0]->getOrder();
                
			// set selected group's title
            $this->view->selectedTitle = $pgi[0]->getTitle();
                
			// get group's parent group
			$pgo->fetchItems(array('id' => $pgi[0]->getParentId()));
			$agParent = $pgo->getItems();
			
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
        preg_match('/(?<=lcm-admin-product-group\/)delete\/.+/', $param, $match);
            
	    if(isset($match[0]) && strlen($match[0]) > 0)
	    {   
			// handle delete
			$productGroupId = trim($match[0], 'delete/');
		
			if($pgo->delete($productGroupId))
			{
				$this->_helper->redirector('', 'lcm-admin-product-group');	
			}
			else
			{
				$this->view->hasMessage = true;
				$this->view->isErr = true;
				$this->view->message = 'Greška kod brisanja. Prvo obrišite proizvode unutar grupe i podgrupe vezane za ovu grupu.';
			}
		}

        // fill the left menu
	    $pgo->fetchItems(array('orderBy' => 'pg.parentId ASC, pg.order ASC'));
        $pgi = $pgo->getItems();

	    // set the left menu groups
		$this->view->productGroupItems = $pgi;
		$this->view->classDisabled = 'disabled';
?>