<?php

class ProductItem
{    
    protected $_id;
    protected $_productGroupId;
    protected $_productGroupTitle;
    protected $_order;
    protected $_price;
    protected $_showCart;
    protected $_active;

    protected $_title;
    protected $_description;
    protected $_text;
    
    protected $_frontImage;
    protected $_images;
    protected $_imagesL;
    protected $_imageDescriptions;
    
    protected $_imageIds;

    protected $_extraInfoItems;

    protected $_linkedProductIds;
    
    public function __construct()
    {        
        $this->_id = 0;
        $this->_productGroupId = 0;
        $this->_order = 0;
        $this->_price = 0;
        $this->_showCart = false;
        $this->_active;
        
        $this->_productExtraInfoId = 0;
                
        $this->_languageId = 0;
        $this->_title = '';
        $this->_description = '';
        $this->_text = '';
        
        $this->_frontImage = '';
		$this->_images = array();
		$this->_imagesL = array();
        $this->_imageDescriptions = array();
        $this->_extraInfoItems = array();
        $this->_linkedProductIds = array();
    }
    
    public function getId()
    {
        return $this->_id;
    }    
    
    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getProductGroupId()
    {
        return $this->_productGroupId;
    }

    public function setProductGroupId($productGroupId)
    {
        $this->_productGroupId = $productGroupId;
    }
    
    public function getProductGroupTitle()
    {
        return $this->_productGroupTitle;
    }

    public function setProductGroupTitle($productGroupTitle)
    {
        $this->_productGroupTitle = $productGroupTitle;
    }    

    public function getOrder()
    {
        return $this->_order;
    }
 
    public function setOrder($order)
    {
        $this->_order = $order;
    }
    
    public function getPrice()
    {
        return $this->_price;
    }
 
    public function setPrice($price)
    {
        $this->_price = $price;
    }
    
    public function getShowCart()
    {
        return $this->_showCart;
    }

    public function setShowCart($showCart)
    {
        $this->_showCart = $showCart;
    }
    
    public function getActive()
    {
        return $this->_active;
    }
    
    public function setActive($active)
    {
        $this->_active = $active;
    }    
      
    public function getProductExtraInfoId()
    {
        return $this->_productExtraInfoId;
    }
    
    public function setProductExtraInfoId($productExtraInfoId)
    {
        $this->_productExtraInfoId = $productExtraInfoId;
    }
        
    public function getLanguageId()
    {
        return $this->_languageId;
    }

    public function setLanguageId($languageId)
    {
        return $this->_languageId = $languageId;
    }

    public function getTitle()
    {
        return $this->_title;
    }
 
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getDescription()
    {
        return $this->_description;
    }
 
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getText()
    {
        return $this->_text;
    }

    public function setText($text)
    {
        $this->_text = $text;
    }

    public function getFrontImage()
    {
		return $this->_frontImage;
    }

    public function setFrontImage($frontImage)
    {
		$this->_frontImage = $frontImage;
    }
    
    public function getImages()
    {
		return $this->_images;
    }

    public function setImages($images)
    {
		$this->_images = $images;
    }
    
    public function addImage($image)
    {
		$this->_images[] = $image;
    }

    public function getImagesL()
    {
		return $this->_imagesL;
    }

    public function setImagesL($imagesL)
    {
		$this->_imagesL = $imagesL;
    }
    
    public function addImageL($imageL)
    {
		$this->_imagesL[] = $imageL;
    }
    
    public function getimageIds()
    {
		return $this->_imageIds;
    }
    
    public function setImageIds($imageId)
    {
		$this->_imageIds = $imageId;
    }
    
    public function addImageId($imageId)
    {
		$this->_imageIds[] = $imageId;
    }    
       
    public function getimageDescriptions()
    {
		return $this->_imageDescriptions;
    }
    
    public function setImageDescriptions($imageDescriptions)
    {
		$this->_imageDescriptions = $imageDescriptions;
    }
    
    public function addImageDescription($imageDescription)
    {
		$this->_imageDescriptions[] = $imageDescription;
    }

    public function getExtraInfoItems()
    {
		return $this->_extraInfoItems;
    }
    
    public function setExtraInfoItems($extraInfoItems)
    {
		$this->_extraInfoItems = $extraInfoItems;
    }
    
    public function addExtraInfoItem($extraInfoItem)
    {
		$this->_extraInfoItems[] = $extraInfoItem;
    }

    public function getLinkedProductIds()
    {
		return $this->_linkedProductIds;
    }
    
    public function setLinkedProductIds($linkedProductIds)
    {
		$this->_linkedProductIds = $linkedProductIds;
    }
    
    public function addLinkedProductId($linkedProductId)
    {
		$this->_linkedProductIds[] = $linkedProductId;
    }
}

class LCM_Product
{
    protected $_database;
    protected $_languageId;
    protected $_docsPath;
    protected $_items;
    
    public function __construct($database, $languageId, $docsPath)
    {
		$this->_database = $database;
        $this->_languageId = $languageId;
        $this->_docsPath = $docsPath;
		$this->_items = array();
    }
    
    public function getItems()
    {
        return $this->_items;
    }
    
    public function setItems($items)
    {
        $this->_items = $items;
    }

	public function deleteProductExtraInfo($productId, $productExtraInfoTypeId)
	{
		$cmd = "
		DELETE FROM	cmsProductExtraInfo
		WHERE		productId = " . $productId . "
					AND productExtraInfoTypeId = " . $productExtraInfoTypeId;

		return $this->_database->getDatabase()->getConnection()->query($cmd);
	}

	public function addProductExtraInfo($productId, $productExtraInfoTypeId)
	{
		$cmd = "
		INSERT IGNORE INTO	cmsProductExtraInfo (id, productId, productExtraInfoTypeId)
		VALUES				(NULL,
							" . $productId . ",
							" . $productExtraInfoTypeId . ")";

		return $this->_database->getDatabase()->getConnection()->query($cmd);
	}
	
	public function fetchProductExtraInfo($productId)
	{
		$cmd = "
		SELECT		pei.id,
					pei.productExtraInfoTypeId,
					peit.description
		FROM		cmsProductExtraInfo AS pei
		INNER JOIN	cmsProductExtraInfoText AS peit ON
					pei.productExtraInfoTypeId = peit.productExtraInfoTypeId
					AND languageId = " . $this->_languageId . "
		WHERE		pei.productId = " . $productId;

		return $this->_database->getDatabase()->fetchAll($cmd);
	}
	
	public function fetchAllProductExtraInfo($languageId)
	{
		$cmd = "
		SELECT		peity.id AS productExtraInfoTypeId,
					peit.description
		FROM		cmsProductExtraInfoType AS peity
		INNER JOIN	cmsProductExtraInfoText AS peit ON
					peity.id = peit.productExtraInfoTypeId
					AND languageId = " . $languageId;

		return $this->_database->getDatabase()->fetchAll($cmd);
	}

    public function save($productData)
    {
        if(!isset($productData['productId']))
		{
            // get highest order index for the new product
            $cmd = "
	    	SELECT	IFNULL(max(cmsProduct.order), 0)
            FROM	cmsProduct
            WHERE	productGroupId = " . $productData['parentId'];
            
	    	$maxOrder = $this->_database->getDatabase()->fetchOne($cmd);

            // insert
            $cmd = "
	    	INSERT  INTO cmsProduct (id, parentId, `order`, price, showCart, productGroupId, active)
            VALUES  (NULL,
		    " . $productData['parentId'] . ",
		    " . ($maxOrder + 1) . ",
		    " . $productData['price'] . ",
		    " . $productData['showCart'] . ",
            " . $productData['parentId'] . ", 
            1)";

	    	$stmt = new Zend_Db_Statement_Mysqli($this->_database->getDatabase(), $cmd);

			if($stmt->execute())
			{
				$productId = $this->_database->getDatabase()->lastInsertId();
	
				$cmd2 = "
				INSERT	INTO cmsProductText (id, productId, languageId, title, description, text)
					VALUES	(NULL,
				" . $productId . ",
				" . $productData['languageId'] . ",
				'" . $productData['title'] . "',
				'" . $productData['description'] . "',
				'" . $productData['text'] . "')";
			}
	}
	else
	{
            $productId = $productData['productId'];
	
	        // determine the ID of a row to be updated
			$cmd3 = "
			SELECT	id
			FROM	cmsProductText
			WHERE	productId = " . $productId . "
					AND languageId = " . $productData['languageId'];

			$recordId = $this->_database->getDatabase()->fetchOne($cmd3);
			
			// update price and cart visibility status
			$cmd4 = "
			UPDATE	cmsProduct
			SET		price = " . $productData['price'] . ",
		    		showCart = " . $productData['showCart'] . "
			WHERE	id = " . $productData['productId'];

	    	$stmt = new Zend_Db_Statement_Mysqli($this->_database->getDatabase(), $cmd4);
			$stmt->execute();
			
			// determine the ID of a row to be updated
			if($recordId)
			{    
				$cmd2 = "
				UPDATE	cmsProductText
				SET		title = '" . $productData['title'] . "',
						description = '" . $productData['description'] . "',
						text = '" . $productData['text'] . "'
				WHERE	id = " . $recordId;
			}
			// if no record ID can be retrieved new text should be inserted (possible insertion of a text in different language)
			else
			{
				$cmd2 = "
				INSERT	INTO cmsProductText
				VALUES	(NULL,
					" . $productId . ",
					" . $productData['languageId'] . ",
					'" . $productData['title'] . "',
					'" . $productData['description'] . "',
					'" . $productData['text'] . "')";
			}
		}

		$this->_database->getDatabase()->getConnection()->query($cmd2);
		return $productId;
    }
    
    public function saveImageText($productImageTextData)
    {
		// check if image text exists for the given language
		$cmd = "
		SELECT	id
		FROM	cmsProductFileText
		WHERE	productFileId = " . $productImageTextData['imageId'] . "
				AND languageId = " . $productImageTextData['languageId'];
		
		$imageTextId = $this->_database->getDatabase()->fetchOne($cmd);
		
		// update
		if($imageTextId)
		{
			$cmd2 = "
			UPDATE	cmsProductFileText
			SET		description = '" . $productImageTextData['imageText'] . "'
			WHERE	id = " . $imageTextId;
		}
		// insert
		else
		{
			$cmd2 = "
			INSERT INTO	cmsProductFileText (id, imageId, languageId, imageText)
			VALUES	(null,
				" . $productImageTextData['imageId'] . ",
				" . $productImageTextData['languageId'] . ",
				'" . $productImageTextData['imageText'] . "')";
		}
	
		$this->_database->getDatabase()->getConnection()->query($cmd2);
    }    
    
    public function removeImageFromProduct($imageId)
    {
	    $cmd = "
	    DELETE FROM	cmsProductFile
	    WHERE		id = " . $imageId;
	    
		return $this->_database->getDatabase()->getConnection()->query($cmd);
    }    
    
    public function addImageToProduct($productImageData)
    {
		// If the new image should be made front image update old front image as non-front
		if($productImageData['isFrontImage'] == '1')
		{
			$cmd = "
			UPDATE	cmsProductFile
			SET		frontImage = 0
			WHERE	frontImage = 1
					AND productId = " . $productImageData['productId'];
			
			$this->_database->getDatabase()->getConnection()->query($cmd);
		}
		
		// Get the new record's order
		$cmd2 = "
		SELECT	MAX(cmsProductFile.order) + 1
		FROM 	cmsProductFile
		WHERE 	productId = " . $productImageData['productId'];
		
		$order = $this->_database->getDatabase()->fetchOne($cmd2);
		
		// If this is the first image linked to this product
		if(!$order)
		{
			$order = 1;
		}
		
		$cmd3 = "
		INSERT INTO	cmsProductFile (id, productId, `order`,filename, frontImage, active)
		VALUES		(NULL,
					" . $productImageData['productId'] . ",
					" . $order . ",
					'" . $productImageData['filename'] . "',
					" . ($productImageData['isFrontImage'] ? "1" : "0") . ",
					1)";
			
		$this->_database->getDatabase()->getConnection()->query($cmd3);
    }

    public function delete($productId)
    {
		$cmd = "
		SELECT	filename
		FROM	cmsProductFile
		WHERE	productId = " . $productId;
		
		$rows = $this->_database->getDatabase()->fetchAll($cmd);
		$filenamesToDelete = array();
		
		$docsPath = $this->_docsPath;
		
		foreach($rows as $row)
		{
			$filenamesToDelete[] = $docsPath . $row['filename'];
		}

        $cmd = "
		DELETE	FROM cmsProduct
        WHERE 	id = " . $productId;

		$this->_database->getDatabase()->getConnection()->query($cmd);
		
		return $filenamesToDelete;		
    }

    public function getBreadCrumb($id)
    {
		$cmd = "
        SELECT		p.productGroupId AS id,
					pt.title AS productTitle,
					pg.parentId,
					IFNULL(pgt.title, '') AS title
        FROM		cmsProduct AS p
        INNER JOIN 	cmsProductGroup AS pg ON
					p.productGroupId = pg.id
        LEFT JOIN 	cmsProductText AS pt ON
					p.id = pt.productId
					AND pt.languageId = " . $this->_languageId . "
        LEFT JOIN 	cmsProductGroupText AS pgt ON
					p.productGroupId = pgt.productGroupId
					AND pgt.languageId = " . $this->_languageId . "
		WHERE		p.id = " . $id;

        $row = $this->_database->getDatabase()->fetchRow($cmd);
        $groups = array();
        $parentId = $row['parentId'];
        array_push($groups, array('id' => 'PRODUCT', 'title' => $row['productTitle']));
        array_push($groups, array('id' => $row['id'], 'title' => $row['title']));

        while($parentId != '0')
		{
			$cmd = "
			SELECT		pg.id,
						pg.parentId,
						IFNULL(pgt.title, '') AS title
			FROM		cmsProductGroup AS pg
			LEFT JOIN 	cmsProductGroupText AS pgt ON
						pg.id = pgt.productGroupId
						and pgt.languageId = " . $this->_languageId . "
			WHERE		pg.id = " . $parentId;
	
			$row = $this->_database->getDatabase()->fetchRow($cmd);
	
			$parentId = $row['parentId'];
			array_push($groups, array('id' => $row['id'], 'title' => $row['title']));
		}
	
        $groups = array_reverse($groups);
		$groups[0]['path'] = $groups[0]['title'];
		
		for($i = 1; $i < count($groups); $i++)
		{
			$groups[$i]['path'] = $groups[$i - 1]['path'] . '/' . $groups[$i]['title'];
		}
		
		return $groups;
    }
    

    public function fetchItems($optionsArray)
    {
        $languageId = isset($optionsArray['languageId']) ? $optionsArray['languageId'] : $this->_languageId;
        
        $cmd = "
        SELECT		p.id,
					p.productGroupId,
					IFNULL(pgt.title, '') AS productGroupTitle,
					p.order,
					p.price,
					p.showCart,
					p.active,
					IFNULL(pt.languageId, '') AS languageId,
					IFNULL(pt.title, '') AS title,
					IFNULL(pt.description, '') AS description,
					IFNULL(pt.text, '') AS text
        FROM		cmsProduct AS p
        LEFT JOIN	cmsProductText AS pt ON
					p.id = pt.productId
					AND pt.languageId = " . $languageId . "
        LEFT JOIN 	cmsProductGroupText AS pgt ON
					p.productGroupId = pgt.productGroupId
					AND pgt.languageId = " . $languageId;
    
        $conditionsArray = array();                    
        if(count($optionsArray) > 0)
		{    
            if(isset($optionsArray['id']))
	    	{
                $conditionsArray[] = 'p.id = ' . $optionsArray['id'];
            }
				
			if(isset($optionsArray['productGroupId']))
			{
                $conditionsArray[] .= 'p.productGroupId = ' . $optionsArray['productGroupId'];
            }
            
			if(isset($optionsArray['order']))
			{
                $conditionsArray[] .= 'p.order = ' . $optionsArray['order'];
            }
            
			if(isset($optionsArray['price']))
			{
                $conditionsArray[] .= "p.price = '" . $optionsArray['price'] . "'";
            }
            
			if(isset($optionsArray['showCart']))
			{
                $conditionsArray[] .= "p.showCart = " . $optionsArray['showCart'];
            }
            
			if(isset($optionsArray['active']))
			{
                $conditionsArray[] = "p.active = " . $optionsArray['active'];
            }
            
			if(isset($optionsArray['title']))
			{
                $conditionsArray[] = "pt.title = '" . $optionsArray['title'] . "'";
            }
				
			if(isset($optionsArray['search']))
			{
                $searchTermsArray = implode('|', $optionsArray['search']);
                $conditionsArray[] = "(pt.title RLIKE '" . $searchTermsArray . "' OR pt.description RLIKE '" . $searchTermsArray . "' OR pt.text RLIKE '" . $searchTermsArray . "')";
            }
				
			if(isset($optionsArray['productGroupTitle']))
			{
                $conditionsArray[] = "pgt.title = '" . $optionsArray['productGroupTitle'] . "'";
            }
				
			if(isset($optionsArray['custom']))
			{
                $conditionsArray[] = " " . $optionsArray['custom'] . " ";
            }

            if(count($conditionsArray) > 0)
			{    
				$conditionsString = '';
				$conditionsString .= ' WHERE ' . implode(' and ', $conditionsArray);
            }

            $cmd .= $conditionsString;
            
			if(isset($optionsArray['orderBy']))
			{
                $cmd .= " ORDER BY " . $optionsArray['orderBy'];
            }
            
			if(isset($optionsArray['limit']))
			{
                $cmd .= " limit " . $optionsArray['limit'][0] . ", " . $optionsArray['limit'][1];
            }
        }
        
        //Zend_Debug::dump($cmd);
    
        $rows = $this->_database->getDatabase()->fetchAll($cmd);
        $this->_items = array();
		
        if(count($rows) > 0)
		{
            foreach($rows as $row)
	    	{
                $i = new ProductItem;
                $i->setId($row['id']);
                $i->setProductGroupId($row['productGroupId']);
                $i->setOrder($row['order']);
                $i->setPrice($row['price']);
                $i->setShowCart($row['showCart']);
                $i->setActive($row['active']);
                $i->setProductGroupTitle($row['productGroupTitle']);
                
                $i->setLanguageId($row['languageId']);
                $i->setTitle($row['title']);
                $i->setDescription($row['description']);
                $i->setText($row['text']);
    
                // get product's front image filename
                $docsPath = $this->_docsPath;
                $cmd = "
                SELECT	filename
                FROM	cmsProductFile
                WHERE	productId = " . $i->getId() . "
						AND frontImage = 1
						AND active = 1";
    
                $filename = $this->_database->getDatabase()->fetchOne($cmd);
                
				if($filename)
				{
                    $i->setFrontImage($docsPath . $filename);
                }
                
                // get set of image filenames and their descriptions displayed with this product
                $cmd = "
                SELECT		pf.id,
							pf.filename,
							pf.order,
							IFNULL(pft.description, '') AS description
                FROM		cmsProductFile AS pf
                LEFT JOIN 	cmsProductFileText AS pft ON
							pf.id = pft.productFileId
							AND pft.languageId = " . $languageId . "
                WHERE		pf.productId = " . $i->getId() . "
							AND pf.active = 1
                ORDER BY	pf.order";

                $rows2 = $this->_database->getDatabase()->fetchAll($cmd);
                
				foreach($rows2 as $row2)
				{
                    $i->addImage($docsPath . $row2['filename']);                   
                    $possibleLPath = $docsPath . preg_replace('/(^.*)\.(.{0,4}$)/', '$1L.$2', $row2['filename']);
							
					if(file_exists($_SERVER{'DOCUMENT_ROOT'} . $possibleLPath))
					{
                        $i->addImageL($possibleLPath);
                    }
                    
				    $i->addImageId($row2['id']);
                    $i->addImageDescription($row2['description']);
                }

                // get set of extra info descriptions associated with this product
                $cmd = "
                SELECT		peit.description
                FROM		cmsProductExtraInfo AS pei
                INNER JOIN	cmsProductExtraInfoType AS peity ON
							pei.productExtraInfoTypeId = peity.id
                INNER JOIN 	cmsProductExtraInfoText AS peit ON
							peity.id = peit.productExtraInfoTypeId
							AND peit.languageId = " . $languageId . "
                WHERE		productId = " . $i->getId();

                $rows3 = $this->_database->getDatabase()->fetchAll($cmd);
                
				foreach($rows3 as $row3)
				{
                    $i->addExtraInfoItem($row3['description']);
                }

                // get set of product IDs linked to this product
                $cmd = "
                SELECT	linkedProductId
                FROM	cmsProductLink
                WHERE	productId = " . $i->getId();
                
                $rows4 = $this->_database->getDatabase()->fetchAll($cmd);
                
				foreach($rows4 as $row4)
				{
                    $i->addLinkedProductId($row4['linkedProductId']);
                }
                
                $this->_items[] = $i;
            }
        }
    }
}

?>