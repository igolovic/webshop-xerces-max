<?php
class ArticleGroupItem
{
    protected $_id;
    protected $_parentId;
    protected $_order;
    protected $_active;
    protected $_languageId;
    protected $_title;
    protected $_description;

    protected $_children;

    public function __construct()
    {
        $this->_id = 0;
        $this->_parentId = 0;
        $this->_order = 0;
        $this->_active = 0;
        $this->_languageId = 0;
        $this->_title = '';
        $this->_description = '';

        $this->_children = array();
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getParentId()
    {
        return $this->_parentId;
    }

    public function setParentId($parentId)
    {
        $this->_parentId = $parentId;
    }

    public function getOrder()
    {
        return $this->_order;
    }

    public function setOrder($order)
    {
        $this->_order = $order;
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

    public function getChildren()
    {
        return $this->_children;
    }

    public function setChildren($children)
    {
        $this->_children = $children;
    }

    public function addChild($child)
    {
        $this->_children[] = $child;
    }
}

class LCM_ArticleGroup
{
    protected $_database;
    protected $_languageId;
    protected $_items;

    public function __construct($database, $languageId)
    {
        $this->_database = $database;
        $this->_languageId = $languageId;
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
        // INSERT
        if (!isset($articleData['articleGroupId']))
        {
            // get highest order index for the new parent group
            $cmd = "
            SELECT  IFNULL(MAX(cmsArticleGroup.order), 0)
            FROM    cmsArticleGroup
            WHERE   parentId = " . $articleData['parentId'];

            $maxOrder = $this
                ->_database
                ->getDatabase()
                ->fetchOne($cmd);

            // INSERT general properties
            $cmd = "
            INSERT  INTO cmsArticleGroup(id, parentId, `order`, active)
            VALUES  (NULL,
                    '" . $articleData['parentId'] . "',
                    " . ($maxOrder + 1) . ",
                    1)";
var_dump($cmd);
            $stmt = new Zend_Db_Statement_Mysqli($this
                ->_database
                ->getDatabase() , $cmd);

            if ($stmt->execute())
            {
                $articleGroupId = $this
                    ->_database
                    ->getDatabase()
                    ->lastInsertId();

                $cmd2 = "
                INSERT  INTO cmsArticleGroupText (id, articleGroupId, languageId, title, description)
                VALUES  (NULL,
                        " . $articleGroupId . ",
                        " . $articleData['languageId'] . ",
                        '" . $articleData['title'] . "',
                        '" . $articleData['description'] . "')";
            }
        }
        else
        {
            $articleGroupId = $articleData['articleGroupId'];

            // determine the ID of a row to be updated
            $cmd3 = "
                SELECT  id
                FROM    cmsArticleGroupText
                WHERE   articleGroupId = " . $articleGroupId . "
                        and languageId = " . $articleData['languageId'];

            $recordId = $this
                ->_database
                ->getDatabase()
                ->fetchOne($cmd3);

            // if record ID is retrieved it's a text UPDATE
            if ($recordId)
            {
                $cmd2 = "
                    UPDATE  cmsArticleGroupText
                    SET     title = '" . $articleData['title'] . "',
                            description = '" . $articleData['description'] . "'
                    WHERE   id = " . $recordId;
            }
            // if no record ID can be retrieved new text should be inserted (possible insertion of a text in different language)
            else
            {
                $cmd2 = "
                    INSERT INTO cmsArticleGroupText
                    VALUES      (NULL,
                                " . $articleGroupId . ",
                                " . $articleData['languageId'] . ",
                                '" . $articleData['title'] . "',
                                '" . $articleData['description'] . "')";
            }
        }

        $this
            ->_database
            ->getDatabase()
            ->getConnection()
            ->query($cmd2);
        return $articleGroupId;
    }

    public function delete($articleGroupId)
    {
        // don't allow delete if this group has children groups
        $cmd = "
		SELECT	id
		FROM	cmsArticleGroup
		WHERE	parentId = " . $articleGroupId . "
		LIMIT	0, 1";

        // don't allow delete if this group contains articles
        $cmd2 = "
		SELECT	id
		FROM	cmsArticle
		WHERE	articleGroupId = " . $articleGroupId . "
		LIMIT	0, 1";

        if (!$this
            ->_database
            ->getDatabase()
            ->fetchOne($cmd) && !$this
            ->_database
            ->getDatabase()
            ->fetchOne($cmd2))
        {
            // delete article group
            $cmd = "
			DELETE FROM cmsArticleGroup
			WHERE 		id = " . $articleGroupId;

            $this
                ->_database
                ->getDatabase()
                ->getConnection()
                ->query($cmd);

            return true;
        }
        else
        {
            return false;
        }
    }

    public function getBreadCrumb($id)
    {
        $cmd = "
        SELECT      ag.id,
                    ag.parentId,
                    IFNULL(agt.title, '') as title
        FROM        cmsArticleGroup as ag
        LEFT JOIN   cmsArticleGroupText as agt on
                    ag.id = agt.articleGroupId
                    and agt.languageId = " . $this->_languageId . "
        WHERE       ag.id = " . $id;

        $row = $this
            ->_database
            ->getDatabase()
            ->fetchRow($cmd);
        $groups = array();
        $parentId = $row['parentId'];
        array_push($groups, array(
            'id' => $row['id'],
            'title' => $row['title']
        ));

        while ($parentId != '0')
        {
            $cmd = "
            SELECT      ag.id,
                        ag.parentId,
                        IFNULL(agt.title, '') as title
            FROM        cmsArticleGroup as ag
            LEFT JOIN   cmsArticleGroupText as agt on
                        ag.id = agt.articleGroupId
                        and agt.languageId = " . $this->_languageId . "
            WHERE       ag.id = " . $parentId;

            $row = $this
                ->_database
                ->getDatabase()
                ->fetchRow($cmd);

            $parentId = $row['parentId'];
            array_push($groups, array(
                'id' => $row['id'],
                'title' => $row['title']
            ));
        }

        $groups = array_reverse($groups);
        $groups[0]['path'] = $groups[0]['title'];

        for ($i = 1;$i < count($groups);$i++)
        {
            $groups[$i]['path'] = $groups[$i - 1]['path'] . '/' . $groups[$i]['title'];
        }

        return $groups;
    }

    public function fetchItems($optionsArray)
    {
        $languageId = isset($optionsArray['languageId']) ? $optionsArray['languageId'] : $this->_languageId;

        $cmd = "
        SELECT      ag.id,
                    ag.parentId,
                    ag.order,
                    ag.active,
                    IFNULL(agt.languageId, '') as languageId,
                    IFNULL(agt.title, '') as title,
                    IFNULL(agt.description, '') as description
        FROM        cmsArticleGroup as ag
        LEFT JOIN   cmsArticleGroupText as agt on
                    ag.id = agt.articleGroupId
                    and agt.languageId = " . $languageId;

        $conditionsArray = array();
        if (count($optionsArray) > 0)
        {
            if (isset($optionsArray['id']))
            {
                $conditionsArray[] = 'ag.id = ' . $optionsArray['id'];
            }

            if (isset($optionsArray['parentId']))
            {
                $conditionsArray[] .= 'ag.parentId = ' . $optionsArray['parentId'];
            }

            if (isset($optionsArray['order']))
            {
                $conditionsArray[] .= 'ag.order = ' . $optionsArray['order'];
            }

            if (isset($optionsArray['active']))
            {
                $conditionsArray[] .= "ag.active = '" . $optionsArray['active'] . "'";
            }

            if (isset($optionsArray['title']))
            {
                $conditionsArray[] .= "agt.title = '" . $optionsArray['title'] . "'";
            }

            if (isset($optionsArray['search']))
            {
                $searchTermsArray = implode('|', $optionsArray['search']);
                $conditionsArray[] = "(agt.title rlike '" . $searchTermsArray . "' or agt.description rlike '" . $searchTermsArray . "')";
            }

            if (isset($optionsArray['custom']))
            {
                $conditionsArray[] = " " . $optionsArray['custom'] . " ";
            }

            $conditionsString = '';

            if (count($conditionsArray) > 0)
            {
                $conditionsString .= ' WHERE ' . implode(' and ', $conditionsArray);
            }

            $cmd .= $conditionsString;

            if (isset($optionsArray['orderBy']))
            {
                $cmd .= " order by " . $optionsArray['orderBy'];
            }

            if (isset($optionsArray['limit']))
            {
                $cmd .= " limit " . $optionsArray['limit'][0] . ", " . $optionsArray['limit'][1];
            }
        }

        // Zend_Debug::dump($cmd);
        // Zend_Debug::dump($conditionsArray);
        $rows = $this
            ->_database
            ->getDatabase()
            ->fetchAll($cmd);

        $this->_items = array();
        if (count($rows) > 0)
        {
            foreach ($rows as $row)
            {
                $i = new ArticleGroupItem;
                $i->setId($row['id']);
                $i->setParentId($row['parentId']);
                $i->setOrder($row['order']);
                $i->setActive($row['active']);
                $i->setLanguageId($row['languageId']);
                $i->setTitle($row['title']);
                $i->setDescription($row['description']);

                $optionsArray['parentId'] = $row['id'];
                $i->setChildren($this->fetchChildren($optionsArray));

                $this->_items[] = $i;
            }
        }
    }

    public function fetchChildren($optionsArray)
    {
        $languageId = isset($optionsArray['languageId']) ? $optionsArray['languageId'] : $this->_languageId;

        $cmd = "
        SELECT      ag.id,
                    ag.parentId,
                    ag.order,
                    ag.active,
                    IFNULL(agt.languageId, '') as languageId,
                    IFNULL(agt.title, '') as title,
                    IFNULL(agt.description, '') as description
	    FROM        cmsArticleGroup as ag
        LEFT JOIN   cmsArticleGroupText as agt on
                    ag.id = agt.articleGroupId
                    and agt.languageId = " . $languageId;
        
        $conditionsArray=array();
        if (count($optionsArray) > 0)
        {
            $conditionsString = ' WHERE ';

            if (isset($optionsArray['id']))
            {
                $conditionsArray[] = 'ag.id = ' . $optionsArray['id'];
            }

            if (isset($optionsArray['parentId']))
            {
                $conditionsArray[] .= 'ag.parentId = ' . $optionsArray['parentId'];
            }

            if (isset($optionsArray['order']))
            {
                $conditionsArray[] .= 'ag.order = ' . $optionsArray['order'];
            }

            if (isset($optionsArray['active']))
            {
                $conditionsArray[] .= "ag.active = '" . $optionsArray['active'] . "'";
            }

            if (isset($optionsArray['title']))
            {
                $conditionsArray[] .= "agt.title = '" . $optionsArray['title'] . "'";
            }

            if (isset($optionsArray['search']))
            {
                $searchTermsArray = implode('|', $optionsArray['search']);
                $conditionsArray[] = "(agt.title rlike '" . $searchTermsArray . "' or agt.description rlike '" . $searchTermsArray . "')";
            }

            if (isset($optionsArray['custom']))
            {
                $conditionsArray[] = " " . $optionsArray['custom'] . " ";
            }

            $conditionsString .= implode(' and ', $conditionsArray);
            $cmd .= $conditionsString;

            if (isset($optionsArray['orderBy']))
            {
                $cmd .= " order by " . $optionsArray['orderBy'];
            }

            if (isset($optionsArray['limit']))
            {
                $cmd .= " limit " . $optionsArray['limit'][0] . ", " . $optionsArray['limit'][1];
            }
        }

        if (is_array($rows = $this
            ->_database
            ->getDatabase()
            ->fetchAll($cmd)))
        {
            $items = array();
            foreach ($rows as $row)
            {
                $i = new ArticleGroupItem;
                $i->setId($row['id']);
                $i->setParentId($row['parentId']);
                $i->setOrder($row['order']);
                $i->setActive($row['active']);
                $i->setLanguageId($row['languageId']);
                $i->setTitle($row['title']);
                $i->setDescription($row['description']);

                $optionsArray['parentId'] = $row['id'];
                $i->setChildren($this->fetchChildren($optionsArray));
                $items[] = $i;
            }

            return $items;
        }
    }
}

?>