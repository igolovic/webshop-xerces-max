<?php
class MenuItem
{
    protected $_id;
    protected $_parentId;
    protected $_order;
    protected $_url;
    protected $_active;

    protected $_languageId;
    protected $_title;

    protected $_children;

    public function __construct()
    {
        $this->_id = 0;
        $this->_parentId = 0;
        $this->_order = 0;
        $this->_url = '';
        $this->_active = 0;

        $this->_languageId = 0;
        $this->_title = '';

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

    public function getUrl()
    {
        return $this->_url;
    }

    public function setUrl($url)
    {
        $this->_url = $url;
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

class LCM_Menu
{
    protected $_database;
    protected $_languageId;
    protected $_items;

    public function __construct($database, $languageId)
    {
        $this->_database = $database;
        $this->_languageId = $languageId;
        $_items = array();
    }

    public function getItems()
    {
        return $this->_items;
    }

    // get one or more items filtered by options together with their children
    public function fetchItems($optionsArray)
    {
        $languageId = isset($optionsArray['languageId']) ? $optionsArray['languageId'] : $this->_languageId;

        $cmd = "
        SELECT      m.id,
                    m.parentId,
                    m.order,
                    m.active,
                    ifnull(mt.languageId, '') as languageId,
                    ifnull(mt.title, '') as title, 
                    ifnull(mt.url, '') as url
        FROM        cmsMenu as m
        LEFT JOIN   cmsMenuText as mt on
                    m.id = mt.menuItemId
                    and mt.languageId = " . $languageId;
        
        $conditionsArray = array();
        if (count($optionsArray) > 0)
        {
            $conditionsString = ' where ';

            if (isset($optionsArray['id']))
            {
                $conditionsArray[] = 'm.id = ' . $optionsArray['id'];
            }

            if (isset($optionsArray['parentId']))
            {
                $conditionsArray[] .= 'm.parentId = ' . $optionsArray['parentId'];
            }

            if (isset($optionsArray['order']))
            {
                $conditionsArray[] .= 'm.order = ' . $optionsArray['order'];
            }

            if (isset($optionsArray['active']))
            {
                $conditionsArray[] .= 'm.active = ' . $optionsArray['active'];
            }
            else
            {
                $conditionsArray[] = "mt.languageId = " . $this->_languageId;
            }

            if (isset($optionsArray['title']))
            {
                $conditionsArray[] .= 'mt.title = ' . $optionsArray['title'];
            }

            if (isset($optionsArray['url']))
            {
                $conditionsArray[] .= 'mt.url = ' . $optionsArray['url'];
            }

            if (isset($optionsArray['custom']))
            {
                $conditionsArray[] = "(" . $optionsArray['custom'] . ")";
            }

            $conditionsString .= implode(' and ', $conditionsArray);
            $cmd .= $conditionsString;

            if (isset($optionsArray['orderBy']))
            {
                $cmd .= " order by " . $optionsArray['orderBy'];
            }
        }

        $rows = $this
            ->_database
            ->getDatabase()
            ->fetchAll($cmd);

        foreach ($rows as $row)
        {
            $i = new MenuItem();
            $i->setId($row['id']);
            $i->setParentId($row['parentId']);
            $i->setOrder($row['order']);
            $i->setUrl($row['url']);
            $i->setActive($row['active']);
            $i->setLanguageId($row['languageId']);
            $i->setTitle($row['title']);

            $optionsArray['parentId'] = $row['id'];
            $i->setChildren($this->fetchChildren($optionsArray));
            $this->_items[] = $i;
        }
    }

    // get element's children recursively
    public function fetchChildren($optionsArray)
    {
        $cmd = "
        select      m.id,
                    m.parentId,
                    m.order,
                    m.active,
                    mt.languageId,
                    mt.title,
                    mt.url
        from        cmsMenu as m
        inner join  cmsMenuText as mt on
                    m.id = mt.menuItemId";
			
        $conditionsArray = array();
        if (count($optionsArray) > 0)
        {
            $conditionsString = ' where ';

            if (isset($optionsArray['id']))
            {
                $conditionsArray[] = 'm.id = ' . $optionsArray['id'];
            }

            if (isset($optionsArray['parentId']))
            {
                $conditionsArray[] .= 'm.parentId = ' . $optionsArray['parentId'];
            }

            if (isset($optionsArray['order']))
            {
                $conditionsArray[] .= 'm.order = ' . $optionsArray['order'];
            }

            if (isset($optionsArray['active']))
            {
                $conditionsArray[] .= 'm.active = ' . $optionsArray['active'];
            }

            if (isset($optionsArray['languageId']))
            {
                $conditionsArray[] .= 'mt.languageId = ' . $optionsArray['languageId'];
            }
            else
            {
                $conditionsArray[] = "mt.languageId = " . $this->_languageId;
            }

            if (isset($optionsArray['title']))
            {
                $conditionsArray[] .= 'mt.title = ' . $optionsArray['title'];
            }

            if (isset($optionsArray['url']))
            {
                $conditionsArray[] .= 'mt.url = ' . $optionsArray['url'];
            }

            if (isset($optionsArray['custom']))
            {
                $conditionsArray[] = "(" . $optionsArray['custom'] . ")";
            }

            $conditionsString .= implode(' and ', $conditionsArray);
            $cmd .= $conditionsString;

            if (isset($optionsArray['orderBy']))
            {
                $cmd .= " order by " . $optionsArray['orderBy'];
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
                $i = new MenuItem();
                $i->setId($row['id']);
                $i->setParentId($row['parentId']);
                $i->setOrder($row['order']);
                $i->setUrl($row['url']);
                $i->setActive($row['active']);
                $i->setLanguageId($row['languageId']);
                $i->setTitle($row['title']);

                $optionsArray['parentId'] = $row['id'];
                $i->setChildren($this->fetchChildren($optionsArray));
                $items[] = $i;
            }

            return $items;
        }
    }
}

?>