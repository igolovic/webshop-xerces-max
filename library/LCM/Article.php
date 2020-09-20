<?php

class ArticleItem
{
    protected $_id;
    protected $_articleGroupId;
    protected $_articleGroupTitle;
    protected $_order;
    protected $_publishDate;
    protected $_active;

    protected $_languageId;
    protected $_title;
    protected $_text;
    protected $_description;

    protected $_frontImage;
    protected $_images;
    protected $_imagesL;
    protected $_imageDescriptions;

    protected $_imageIds;
    
    public function __construct()
    {
		$this->_id = 0;
		$this->_articleGroupId = 0;
		$this->_order = 0;
		$this->_publishDate = '0000-00-00 00:00:00';
		$this->_active = '0';
		
		$this->_languageId = 0;
		$this->_title = '';
		$this->_text = '';
		$this->_description = '';
		
		$this->_frontImage = '';
		$this->_images = array();
		$this->_imagesL = array();
		$this->_imagesDescriptions = array();
		
		$this->_imageIds = array();
	
        $this->_linkedArticleIds = array();
    }
    
    public function getId()
    {
		return $this->_id;
    }
    
    public function setId($id)
    {
		$this->_id = $id;
    }

    public function getArticleGroupId()
    {
		return $this->_articleGroupId;
    }
    
    public function setArticleGroupId($articleGroupId)
    {
		$this->_articleGroupId = $articleGroupId;
    }

    public function getArticleGroupTitle()
    {
		return $this->_articleGroupTitle;
    }
    
    public function setArticleGroupTitle($articleGroupTitle)
    {
		$this->_articleGroupTitle = $articleGroupTitle;
    }

    public function getOrder()
    {
		return $this->_order;
    }
    
    public function setOrder($order)
    {
		$this->_order = $order;
    }

    public function getPublishDate()
    {
		return $this->_publishDate;
    }
    
    public function setPublishDate($publishDate)
    {
		$this->_publishDate = $publishDate;
    }

    public function getActive()
    {
		return $this->_active;
    }
    
    public function setActive($active)
    {
		$this->_active = $active;
    }

    public function getLanguageId()
    {
		return $this->_languageId;
    }
    
    public function setLanguageId($languageId)
    {
		$this->_languageId = $languageId;
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
    
    public function getLinkedArticleIds()
    {
		return $this->_linkedArticleIds;
    }
    
    public function setLinkedArticleIds($linkedArticleIds)
    {
		$this->_linkedArticleIds = $linkedArticleIds;
    }
    
    public function addLinkedArticleId($linkedArticleId)
    {
		$this->_linkedArticleIds[] = $linkedArticleId;
    }
}

class LCM_Article
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

    public function save($articleData)
    {
        if(!isset($articleData['articleId']))
		{	
            // get highest order index for the new article
            $cmd = "
	    	SELECT	IFNULL(max(cmsArticle.order), 0)
            FROM	cmsArticle
            WHERE	articleGroupId = " . $articleData['parentId'];
            
	    	$maxOrder = $this->_database->getDatabase()->fetchOne($cmd);
			
            // insert
            $cmd = "
	    	INSERT  INTO cmsArticle(id, articleGroupId, `order`, publishDate, active)
            VALUES  (NULL,
		    " . $articleData['parentId'] . ",
		    " . ($maxOrder + 1) . ",
		    " . $articleData['publishDate'] . ",
		    1)";

	    	$stmt = new Zend_Db_Statement_Mysqli($this->_database->getDatabase(), $cmd);	
	
			if($stmt->execute())
			{
					$articleId = $this->_database->getDatabase()->lastInsertId();
	
					$cmd2 = "
					INSERT	INTO cmsArticleText(id, articleId, languageId, title, description, text)
					VALUES	(NULL,
					" . $articleId . ",
					" . $articleData['languageId'] . ",
					'" . $articleData['title'] . "',
					'" . $articleData['description'] . "',
					'" . $articleData['text'] . "')";
			}
		}
		else
		{
			$articleId = $articleData['articleId'];
		
			// determine the ID of a row to be updated
			$cmd3 = "
			SELECT	id
			FROM	cmsArticleText
			WHERE	articleId = " . $articleId . "
					AND languageId = " . $articleData['languageId'];
	
			$recordId = $this->_database->getDatabase()->fetchOne($cmd3);
	
			// determine the ID of a row to be updated
			if($recordId)
			{
				$cmd4 = "
				UPDATE	cmsArticle
				SET		publishDate = " . $articleData['publishDate'] . "
				WHERE	id = " . $articleId;
				
				$this->_database->getDatabase()->getConnection()->query($cmd4);

				$cmd2 = "
				UPDATE	cmsArticleText
				SET		title = '" . trim($articleData['title']) . "',
						description = '" . trim($articleData['description']) . "',
						text = '" . trim($articleData['text']) . "'
				WHERE	id = " . $recordId;
			}
			// if no record ID can be retrieved new text should be inserted (possible insertion of a text in different language)
			else
			{
				$cmd2 = "
				INSERT	INTO cmsArticleText(id, articleId, languageId, title, description, text)
				VALUES	(NULL,
					" . $articleId . ",
					" . $articleData['languageId'] . ",
					'" . trim($articleData['title']) . "',
					'" . trim($articleData['description']) . "',
					'" . trim($articleData['text']) . "')";
			}
		}

		$this->_database->getDatabase()->getConnection()->query($cmd2);
		return $articleId;
    }
    
    public function saveImageText($articleImageTextData)
    {
		// check if image text exists for the given language
		$cmd = "
		SELECT	id
		FROM	cmsArticleFileText
		WHERE	articleFileId = " . $articleImageTextData['imageId'] . "
				AND languageId = " . $articleImageTextData['languageId'];
	
		$imageTextId = $this->_database->getDatabase()->fetchOne($cmd);
		
		// update
		if($imageTextId)
		{
			$cmd2 = "
			UPDATE	cmsArticleFileText
			SET		description = '" . $articleImageTextData['imageText'] . "'
			WHERE	id = " . $imageTextId;
		}
		// insert
		else
		{
			$cmd2 = "
			INSERT INTO	cmsArticleFileText(id, articleFileId, languageId, description)
			VALUES	(null,
				" . $articleImageTextData['imageId'] . ",
				" . $articleImageTextData['languageId'] . ",
				'" . $articleImageTextData['imageText'] . "')";
		}
	
		$this->_database->getDatabase()->getConnection()->query($cmd2);
    }
    
    public function removeImageFromArticle($imageId)
    {
	    $cmd = "
	    DELETE FROM	cmsArticleFile
	    WHERE	id = " . $imageId;
	    
		return $this->_database->getDatabase()->getConnection()->query($cmd);
    }
    
    public function addImageToArticle($articleImageData)
    {
		// If the new image should be made front image update old front image as non-front
		if($articleImageData['isFrontImage'] == '1')
		{
			$cmd = "
			UPDATE	cmsArticleFile
			SET		frontImage = 0
			WHERE	frontImage = 1
					AND articleId = " . $articleImageData['articleId'];
	
			$this->_database->getDatabase()->getConnection()->query($cmd);
		}
		
		// Get the new record's order
		$cmd2 = "
		SELECT	MAX(cmsArticleFile.order) + 1
		FROM 	cmsArticleFile
		WHERE 	articleId = " . $articleImageData['articleId'];
		
		$order = $this->_database->getDatabase()->fetchOne($cmd2);

		// If this is the first image linked to this article
		if(!$order)
		{
			$order = 1;
		}
		
		$cmd3 = "
		INSERT INTO	cmsArticleFile(id, articleId, `order`, filename, frontImage, active)
		VALUES		(NULL,
				" . $articleImageData['articleId'] . ",
				" . $order . ",
				'" . $articleImageData['filename'] . "',
				" . ($articleImageData['isFrontImage'] ? "1" : "0") . ",
				1)";
			
		$this->_database->getDatabase()->getConnection()->query($cmd3);
    }

    public function delete($articleId)
    {
		$cmd = "
		SELECT	filename
		FROM	cmsArticleFile
		WHERE	articleId = " . $articleId;
		
		$rows = $this->_database->getDatabase()->fetchAll($cmd);
		$filenamesToDelete = array();
		$docsPath = $this->_docsPath;
		
		foreach($rows as $row)
		{
			$filenamesToDelete[] = $docsPath . $row['filename'];
		}

        $cmd = "
		DELETE	FROM cmsArticle
        WHERE 	id = " . $articleId;

		$this->_database->getDatabase()->getConnection()->query($cmd);
		
		return $filenamesToDelete;
    }

    public function getBreadCrumb($id)
    {
		$cmd = "
		SELECT		a.articleGroupId AS id,
					at.title AS articleTitle,
					ag.parentId,
					IFNULL(agt.title, '') AS title
		FROM		cmsArticle AS a
		INNER JOIN 	cmsArticleGroup AS ag ON
					a.articleGroupId = ag.id
		INNER JOIN 	cmsArticleText AS at ON
					a.id = at.articleId
					AND at.languageId = " . $this->_languageId . "
		INNER JOIN 	cmsArticleGroupText AS agt ON
					a.articleGroupId = agt.articleGroupId
					AND agt.languageId = " . $this->_languageId . "
		WHERE		a.id = " . $id;
	
		$row = $this->_database->getDatabase()->fetchRow($cmd);
		$groups = array();
		$parentId = $row['parentId'];
		array_push($groups, array('id' => 'ARTICLE', 'title' => $row['articleTitle']));
		array_push($groups, array('id' => $row['id'], 'title' => $row['title']));
	
		while($parentId != '0')
		{
			$cmd = "
			SELECT		ag.id,
						ag.parentId,
						IFNULL(agt.title, '') AS title
			FROM		cmsArticleGroup AS ag
			INNER JOIN 	cmsArticleGroupText AS agt ON
						ag.id = agt.articleGroupId
						AND agt.languageId = " . $this->_languageId . "
			WHERE		ag.id = " . $parentId;
	
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
		SELECT	    a.id,
					a.articleGroupId,
					IFNULL(agt.title, '') AS articleGroupTitle,
					a.order,
					a.publishDate,
					a.active,
					IFNULL(at.languageId, '') AS languageId,
					IFNULL(at.title, '') AS title,
					IFNULL(at.description, '') AS description,
					IFNULL(at.text, '') AS text
		FROM	    cmsArticle AS a
		LEFT JOIN 	cmsArticleText AS at ON
					a.id = at.articleId
					AND at.languageId = " . $languageId . "
		LEFT JOIN 	cmsArticleGroupText AS agt ON
					a.articleGroupId = agt.articleGroupId
					AND agt.languageId = " . $languageId;

		$conditionsArray = array();
		if(count($optionsArray) > 0)
		{    			
			if(isset($optionsArray['id']))
			{
				$conditionsArray[] = 'a.id = ' . $optionsArray['id'];
			}
			
			if(isset($optionsArray['articleGroupId']))
			{
				$conditionsArray[] .= 'a.articleGroupId = ' . $optionsArray['articleGroupId'];
			}
			
			if(isset($optionsArray['order']))
			{
				$conditionsArray[] .= 'a.order = ' . $optionsArray['order'];
			}
			
			if(isset($optionsArray['publishDate']))
			{
				$conditionsArray[] .= "a.publishDate = '" . $optionsArray['publishDate'] . "'";
			}
			
			if(isset($optionsArray['active']))
			{
				$conditionsArray[] = "a.active = " . $optionsArray['active'];
			}
			
			if(isset($optionsArray['title']))
			{
				$conditionsArray[] = "at.title = '" . $optionsArray['title'] . "'";
			}
			
			if(isset($optionsArray['search']))
			{
				$searchTermsArray = implode('|', $optionsArray['search']);
				$conditionsArray[] = "(at.title RLIKE '" . $searchTermsArray . "' OR at.description RLIKE'" . $searchTermsArray . "' OR at.text RLIKE '" . $searchTermsArray . "')";
			}
			
			if(isset($optionsArray['articleGroupTitle']))
			{
				$conditionsArray[] = "agt.title = '" . $optionsArray['articleGroupTitle'] . "'";
			}
			
			if(isset($optionsArray['custom']))
			{
				$conditionsArray[] = " " . $optionsArray['custom'] . " ";
			}
			
			if(count($conditionsArray) > 0)
			{    
				$conditionsString = '';
				$conditionsString .= ' WHERE ' . implode(' AND ', $conditionsArray);
			}
			
			$cmd .= $conditionsString;
			
			if(isset($optionsArray['orderBy']))
			{
				$cmd .= " ORDER BY " . $optionsArray['orderBy'];
			}
			
			if(isset($optionsArray['limit']))
			{
				$cmd .= " LIMIT " . $optionsArray['limit'][0] . ", " . $optionsArray['limit'][1];
			}
		}
		
		//Zend_Debug::dump($cmd);
	
		$rows = $this->_database->getDatabase()->fetchAll($cmd);
		$this->_items = array();
		
		if(count($rows) > 0)
		{
			foreach($rows as $row)
			{	
				$i = new ArticleItem;
				$i->setId($row['id']);
				$i->setArticleGroupId($row['articleGroupId']);
				$i->setArticleGroupTitle($row['articleGroupTitle']);
				$i->setOrder($row['order']);
				$i->setPublishDate($row['publishDate']);
				$i->setActive($row['active']);
				$i->setLanguageId($row['languageId']);
				$i->setTitle($row['title']);
				$i->setDescription($row['description']);
				$i->setText($row['text']);
		
				// get article front image
				$docsPath = $this->_docsPath;
				$cmd = "
				SELECT	filename
				FROM	cmsArticleFile
				WHERE	articleId = " . $i->getId() . "
						AND frontImage = 1
						AND active = 1";
		
				$filename = $this->_database->getDatabase()->fetchOne($cmd);
				
				if($filename)
				{
					$i->setFrontImage($docsPath . $filename);
				}
				
				// get set of images displayed with this article
				$cmd = "
				SELECT		af.id,
							af.filename,
							af.frontImage,
							IFNULL(aft.description, '') AS description
				FROM		cmsArticleFile AS af
				LEFT JOIN 	cmsArticleFileText AS aft ON
							af.id = aft.articleFileId
							AND aft.languageId = " . $languageId . "
				WHERE		af.articleId = " . $i->getId() . "
							AND af.active = 1
				ORDER BY	af.order";
		
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
				
				// get set of article IDs linked to this article
				$cmd = "
				SELECT	linkedArticleId
				FROM	cmsArticleLink
				WHERE	articleId = " . $i->getId();
				
				$rows4 = $this->_database->getDatabase()->fetchAll($cmd);
						
				foreach($rows4 as $row4)
				{
					$i->addLinkedArticleId($row4['linkedArticleId']);
				}
				
				$this->_items[] = $i;
			}
		}
    }
}

?>