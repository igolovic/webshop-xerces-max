<?php

	    $ao = $this->_cms->getArticleObject();
	    
	    // reset any article data
	    $this->view->selectedArticleId = $this->view->currentArticle_Id = 0;
	    $this->view->currentArticle_Title = '';
	    $this->view->currentArticle_GroupId = 0;
	    $this->view->currentArticle_GroupTitle = '';
	    $this->view->currentArticle_Order = 0;
	    $this->view->currentArticle_Active = 'false';
	    $this->view->currentArticle_Images_Descriptions = array();
	    $this->view->maxThumbHeight = $this->_cms->getMaxThumbHeight();
		$this->view->currentArticle_DisplayDate = "checked='checked'";

	    if(isset($_POST['languageId']))
	    {
			$this->_cms->setLanguage($_POST['languageId']);
	    }
	    
	    $this->view->selectedLanguageId = $this->_cms->getLanguage();
	    
	    // fill the article group items for the upper left combo box
	    $ago = $this->_cms->getArticleGroupObject();
	    $ago->fetchItems(array('orderBy' => 'ag.parentId ASC, ag.order ASC'));
	    $agi = $ago->getItems();
	    
	    // set the upper left combo box contents
	    $this->view->articleGroupItems = $agi;
	    
	    // fill the languages
	    $lgo = $this->_cms->getLanguageObject();
	    $lgo->fetchItems(array());
	    $li = $lgo->getItems();
	    
	    // set the language combo box
	    $this->view->languageItems = $li;

	    // presume enabled parent group selection (insertion of a new article and not viewing existing)
	    $this->view->parentSelectEnabled = true;
	    
	    $this->view->hasMessage = false;
	    $this->view->message = '';
	    $this->_helper->viewRenderer('admin-article');

	    // save image text
	    if(isset($_POST['selectedArticleId']) && isset($_POST['inpSelectedImage']) && isset($_POST['saveImageText']))
	    {
			$imageIdFilename = explode('|', $_POST['inpSelectedImage']);			
			$textVariableName = 'imageText' . $imageIdFilename[0];
			
			if(isset($_POST[$textVariableName]))
			{
				    $ao->saveImageText(array(	'imageId' => $imageIdFilename[0],
								     			'languageId' => $this->view->selectedLanguageId,
									     		'imageText' => str_replace("'", "&#039;", $_POST[$textVariableName])));
			}
	    }
	    
	    // delete image
	    if(isset($_POST['selectedArticleId']) && isset($_POST['inpSelectedImage']) && isset($_POST['deleteImage']))
	    {
			$imageIdFilename = explode('|', $_POST['inpSelectedImage']);
			$this->view->hasMessage = true;

			if($ao->removeImageFromArticle($imageIdFilename[0]))
			{
				    if(unlink(substr($imageIdFilename[1], 1)))
				    {
						$this->view->message = 'Slika je uspješno obrisana.';
						$possibleLPath = preg_replace('/(^.*)\.(.{0,4}$)/', '$1L.$2', $imageIdFilename[1]);
						
						if(file_exists(substr($possibleLPath, 1)))
						{
							$this->view->message = 'Slika i veća verzija slike su uspješno obrisane.';
							unlink(substr($possibleLPath, 1));
						}
				    }
				    else
				    {
						$this->view->isErr = true;
						$this->view->message = 'Greška kod brisanja, datoteka nije pronađena.';
				    }
			}
			else
			{
				    $this->view->isErr = true;
				    $this->view->message = 'Greška kod brisanja iz baze.';
			}
	    }

	    // image upload
	    if(isset($_POST['selectedArticleId']) && isset($_POST['saveImage']))
	    {		
			// where the file is going to be placed
			$filename = basename($_FILES['newImage']['name']);
			$uploadDirPath = substr($this->_cms->getDocsPath(), 1);
			$filePath = $uploadDirPath . $filename;
			
			$this->view->hasMessage = true;
			
			if(is_file($filePath))
			{
				    $this->view->isErr = true;
				    $this->view->message = 'Datoteka s tim nazivom već postoji, preimenujte datoteku i pokušajte ponovno.';
			}
			else
			{
				if(move_uploaded_file($_FILES['newImage']['tmp_name'], $filePath))
				{
					// write to database only if this is not a large version of previously uploaded image
					preg_match('/.*L\.[A-Za-z]{0,4}$/', $_FILES['newImage']['name'], $match);
					
					if(!isset($match[0]))
					{
							$ao->addImageToArticle(array('articleId' => $_POST['selectedArticleId'],
														 'isFrontImage' => (isset($_POST['isFrontImage']) ? true : false),
														 'filename' => $filename));
					}
							
					$this->view->isErr = false;
					$this->view->message = 'Dodavanje datoteke je uspjelo.';
				}
				else
				{
					$this->view->isErr = true;
					$this->view->message = 'Dodavanje datoteke nije uspjelo.';
				}
			}
	    }
  
		// save
	    if(isset($_POST['save']))
	    {
			// ID of the selected article is not defined
			if($_POST['selectedArticleId'] == 0)
			{
				    // insert
				    $lastInsertId = $ao->save(array('parentId' => $_POST['parentId'],
													'publishDate' => (isset($_POST['publishDate']) ? "NOW()" : "'0000-00-00 00:00:00'"),
												    'languageId' => $this->view->selectedLanguageId,
						     						'title' => $_POST['title'],
									     			'description' => str_replace("'", "&#039;", $_POST['description']),
								     				'text' => str_replace("'", "&#039;", $_POST['text'])));
			}
			// ID of the selected article is defined
			else
			{
				    // update
				    $lastInsertId = $ao->save(array('articleId' => $_POST['articleId'],
													'publishDate' => (isset($_POST['publishDate']) ? "NOW()" : "'0000-00-00 00:00:00'"),
						     						'parentId' => isset($_POST['parentId']) ? $_POST['parentId'] : '',
						     						'languageId' => $this->view->selectedLanguageId,
								     				'title' => $_POST['title'],
									     			'description' => str_replace("'", "&#039;", $_POST['description']),
								     				'text' => str_replace("'", "&#039;", $_POST['text'])));
			}
	    }
	    
	    // article ID is defined and delete is specified
	    if(isset($_POST['selectedArticleId']) && isset($_POST['deleteArticle']))
	    {
			$filenamesToDelete = $ao->delete($_POST['selectedArticleId']);
						
			foreach($filenamesToDelete as $filename)
			{	
				if(unlink(substr($filename, 1)))
				{
					$possibleLPath = preg_replace('/(^.*)\.(.{0,4}$)/', '$1L.$2', $filename);
					
					if(file_exists(substr($possibleLPath, 1)))
					{
						unlink(substr($possibleLPath, 1));
					}
				}
				else
				{
					$this->view->isErr = true;
					$this->view->message = 'Greška kod brisanja, datoteka nije pronađena.';		
				}
			}
	    }
	    
	    // handle article items combo box and article data according to article group selection
	    if(isset($_POST['articleGroupId']))
	    {
			// get article items belonging to the group
			$ao->fetchItems(array(	'articleGroupId' => $_POST['articleGroupId'],
					      			'orderBy' => 'a.articleGroupId ASC, a.order ASC',
					      			'languageId' => $this->view->selectedLanguageId));
								
			$ai = $ao->getItems();
			
			// set article items belonging to the selected group
			$this->view->articleItems = $ai;
			
			// set selected article group item's ID
			$this->view->selectedArticleGroupId = $_POST['articleGroupId'];
			$this->view->saveImageEnabled = false;

			// set article data
			if(!isset($_POST['deleteArticle']) && isset($_POST['articleId']) && $_POST['articleId'] > 0)
			{
				$this->view->saveImageEnabled = true;
				
				$ao->fetchItems(array('id' => $_POST['articleId']));
				$ai = $ao->getItems();
	
				// selected group has not changed				    
				if($_POST['articleGroupId'] == $ai[0]->getArticleGroupId())
				{						
					$ao->fetchItems(array('id' => $_POST['articleId'], 'languageId' => $this->view->selectedLanguageId));
					$ai = $ao->getItems();
					
					$this->view->selectedArticleId = $this->view->currentArticle_Id = $ai[0]->getId();
					$this->view->currentArticle_GroupId = $ai[0]->getArticleGroupId();
					$this->view->currentArticle_GroupTitle = $ai[0]->getArticleGroupTitle();
					$this->view->currentArticle_Order = $ai[0]->getOrder();
					$this->view->currentArticle_Active = $ai[0]->getActive() == '1' ? "checked='checked'" : "";
					$this->view->currentArticle_DisplayDate = $ai[0]->getPublishDate() != '0000-00-00 00:00:00' ? "checked='checked'" : "";

					// handle textual properties of article
					$this->view->currentArticle_Title = $ai[0]->getTitle();
					$this->view->currentArticle_Description = $ai[0]->getDescription();
					$this->view->currentArticle_Text = $ai[0]->getText();					

					// handle article images
					$this->view->frontImage = $ai[0]->getFrontImage();
					
					$imgArray = $ai[0]->getImages();
					$descArray = $ai[0]->getImageDescriptions();
					$idArray = $ai[0]->getImageIds();
					$imgDescIdArray = array();
					
					for($i=0; $i<count($imgArray); $i++)
					{
							$imgDescIdArray[] = array(	isset($imgArray[$i])	? $imgArray[$i]	: '',
														isset($descArray[$i])	? $descArray[$i]: '',
														isset($idArray[$i])		? $idArray[$i]	: '');
					}
	
					$this->view->currentArticle_Images_Descriptions = $imgDescIdArray;
					
					$this->view->parentSelectEnabled = false;
				}
			}
	    }
?>