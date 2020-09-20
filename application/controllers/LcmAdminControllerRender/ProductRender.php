<?php
	    $po = $this->_cms->getProductObject();
	    
	    // reset any product data
	    $this->view->selectedProductId = $this->view->currentProduct_Id = 0;
	    $this->view->currentProduct_Title = '';
	    $this->view->currentProduct_GroupId = 0;
	    $this->view->currentProduct_GroupTitle = '';
	    $this->view->currentProduct_Order = 0;
	    $this->view->currentProduct_Active = 'false';
	    $this->view->currentProduct_Images_Descriptions = array();
	    $this->view->currentProduct_Price = "0";
	    $this->view->currentProduct_ShowCart = true;
	    $this->view->maxThumbHeight = $this->_cms->getMaxThumbHeight();
		$this->view->productExtraInfoItems = array();

	    if(isset($_POST['languageId']))
	    {
			$this->_cms->setLanguage($_POST['languageId']);
	    }
	    
	    $this->view->selectedLanguageId = $this->_cms->getLanguage();
		
		$this->view->allProductExtraInfoItems = $po->fetchAllProductExtraInfo($this->view->selectedLanguageId);
			    
	    // fill the product group items for the upper left combo box
	    $pgo = $this->_cms->getProductGroupObject();
	    $pgo->fetchItems(array('orderBy' => 'pg.parentId ASC, pg.order ASC'));
	    $pgi = $pgo->getItems();
	    
	    // set the upper left combo box contents
	    $this->view->productGroupItems = $pgi;
	    
	    // fill the languages
	    $lgo = $this->_cms->getLanguageObject();
	    $lgo->fetchItems(array());
	    $li = $lgo->getItems();
	    
	    // set the language combo box
	    $this->view->languageItems = $li;

	    // presume enabled parent group selection (insertion of a new product and not viewing existing)
	    $this->view->parentSelectEnabled = true;
	    
	    $this->view->hasMessage = false;
	    $this->view->message = '';
	    $this->_helper->viewRenderer('admin-product');

		// add product extra info
		if(isset($_POST['addProductExtraInfo']) && isset($_POST['selectedProductId']) && isset($_POST['selectedProductExtraInfo']))
		{
			$po->addProductExtraInfo($_POST['selectedProductId'], $_POST['selectedProductExtraInfo']);
		}
		
		// delete product extra info
		if(isset($_POST['deleteProductExtraInfo']) && isset($_POST['selectedProductId']) && isset($_POST['inpSelectedProductExtraInfo']))
		{
			$po->deleteProductExtraInfo($_POST['selectedProductId'], $_POST['inpSelectedProductExtraInfo']);
		}		

	    // save image text
	    if(isset($_POST['selectedProductId']) && isset($_POST['inpSelectedImage']) && isset($_POST['saveImageText']))
	    {
			$imageIdFilename = explode('|', $_POST['inpSelectedImage']);			
			$textVariableName = 'imageText' . $imageIdFilename[0];
			
			if(isset($_POST[$textVariableName]))
			{
				    $po->saveImageText(array('imageId' => $imageIdFilename[0],
										     'languageId' => $this->view->selectedLanguageId,
										     'imageText' => str_replace("'", "&#039;", $_POST[$textVariableName])));
			}
	    }
	    
	    // delete image
	    if(isset($_POST['selectedProductId']) && isset($_POST['inpSelectedImage']) && isset($_POST['deleteImage']))
	    {
			$imageIdFilename = explode('|', $_POST['inpSelectedImage']);
			$this->view->hasMessage = true;

			if($po->removeImageFromProduct($imageIdFilename[0]))
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
	    if(isset($_POST['selectedProductId']) && isset($_POST['saveImage']))
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
								$po->addImageToProduct(array('productId' => $_POST['selectedProductId'],
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
			// ID of the selected product is not defined
			if($_POST['selectedProductId'] == 0)
			{
				    // insert
				    $lastInsertId = $po->save(array(
						     'parentId' => $_POST['parentId'],
						     'languageId' => $this->view->selectedLanguageId,
							 'price' => is_numeric($_POST['price']) ? $_POST['price'] : '0',
							 'showCart' => isset($_POST['showCart']) ? '1' : '0',
						     'title' => $_POST['title'],
						     'description' => str_replace("'", "&#039;", $_POST['description']),
						     'text' => str_replace("'", "&#039;", $_POST['text'])));
			}
			// ID of the selected product is defined
			else
			{
				    // update
				    $lastInsertId = $po->save(array(
						     'productId' => $_POST['productId'],
						     'parentId' => isset($_POST['parentId']) ? $_POST['parentId'] : '',
						     'languageId' => $this->view->selectedLanguageId,
							 'price' => is_numeric($_POST['price']) ? $_POST['price'] : '0',
							 'showCart' => isset($_POST['showCart']) ? '1' : '0',
						     'title' => $_POST['title'],
						     'description' => str_replace("'", "&#039;", $_POST['description']),
						     'text' => str_replace("'", "&#039;", $_POST['text'])));
			}
	    }
	    
	    // ID is defined and delete is specified
	    if(isset($_POST['selectedProductId']) && isset($_POST['deleteProduct']))
	    {
			$filenamesToDelete = $po->delete($_POST['selectedProductId']);

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
	    
	    // handle product items combo box and product data according to product group selection
	    if(isset($_POST['productGroupId']))
	    {
			// get product items belonging to the group
			$po->fetchItems(array(	'productGroupId' => $_POST['productGroupId'],
					      			'orderBy' => 'p.productGroupId ASC, p.order ASC',
					      			'languageId' => $this->view->selectedLanguageId));
			$pi = $po->getItems();
			
			// set product items belonging to the selected group
			$this->view->productItems = $pi;
			
			// set selected product group item's ID
			$this->view->selectedProductGroupId = $_POST['productGroupId'];

			$this->view->saveImageEnabled = false;

			// set product data
			if(!isset($_POST['deleteProduct']) && isset($_POST['productId']) && $_POST['productId'] > 0)
			{
				    $this->view->saveImageEnabled = true;
				    
				    $po->fetchItems(array('id' => $_POST['productId']));
				    $pi = $po->getItems();
					
				    // selected group has not changed				    
				    if($_POST['productGroupId'] == $pi[0]->getProductGroupId())
				    {						
						$po->fetchItems(array('id' => $_POST['productId'], 'languageId' => $this->view->selectedLanguageId));
						$pi = $po->getItems();
						
						$this->view->selectedProductId = $this->view->currentProduct_Id = $pi[0]->getId();
						$this->view->currentProduct_GroupId = $pi[0]->getProductGroupId();
						$this->view->currentProduct_GroupTitle = $pi[0]->getProductGroupTitle();
						$this->view->currentProduct_Order = $pi[0]->getOrder();
						$this->view->currentProduct_Active = $pi[0]->getActive() == '1' ? "checked='checked'" : "";
						
						// handle textual properties of a product
						$this->view->currentProduct_Title = $pi[0]->getTitle();
						$this->view->currentProduct_Description = $pi[0]->getDescription();
						$this->view->currentProduct_Text = $pi[0]->getText();
						
						// handle specific properties of a product
						$this->view->currentProduct_Price = $pi[0]->getPrice();
						$this->view->currentProduct_ShowCart = $pi[0]->getShowCart();

						// handle product images
						$this->view->frontImage = $pi[0]->getFrontImage();

						$imgArray = $pi[0]->getImages();
						$descArray = $pi[0]->getImageDescriptions();
						$idArray = $pi[0]->getImageIds();
						
						$imgDescIdArray = array();
						
						for($i=0; $i<count($imgArray); $i++)
						{
							    $imgDescIdArray[] = array(	isset($imgArray[$i]) ? $imgArray[$i] : null,
										      				isset($descArray[$i]) ? $descArray[$i] : null,
										      				isset($idArray[$i]) ? $idArray[$i] : null);
						}

						$this->view->currentProduct_Images_Descriptions = $imgDescIdArray;
						$this->view->parentSelectEnabled = false;
						
						// handle extra product info items
						$this->view->productExtraInfoItems = $po->fetchProductExtraInfo($pi[0]->getId());
				    }
			}
	    }
?>