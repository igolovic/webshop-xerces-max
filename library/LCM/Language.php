<?php
class LanguageItem
{
    protected $_id;
    protected $_title;

    public function __construct()
    {
        $this->_id = 0;
        $this->_title = '';
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }
}

class LCM_Language
{
    protected $_database;
    protected $_items;
    protected $_languageId;
    protected $_cms;
    public function __construct($database, $languageId, $cms)
    {
        $this->_database = $database;
        $this->_languageId = $languageId;
        $this->_cms = $cms;
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

    // get one or more items filtered by options
    public function fetchItems($optionsArray)
    {
        $languageId = isset($optionsArray['languageId']) ? $optionsArray['languageId'] : $this->_languageId;

        $cmd = "
	    SELECT	id,
		        title
	    FROM	cmsLanguage";

        $conditionsArray = array();
        if (count($optionsArray) > 0)
        {
            $conditionsString = ' where ';

            if (isset($optionsArray['id']))
            {
                $conditionsArray[] = 'a.id = ' . $optionsArray['id'];
            }

            if (isset($optionsArray['title']))
            {
                $conditionsArray[] = "at.title = '" . $optionsArray['title'] . "'";
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

        //Zend_Debug::dump($cmd);
        $rows = $this
            ->_database
            ->getDatabase()
            ->fetchAll($cmd);

        $this->_items = array();
        if (count($rows) > 0)
        {
            foreach ($rows as $row)
            {
                $i = new LanguageItem;
                $i->setId($row['id']);
                $i->setTitle($row['title']);

                $this->_items[] = $i;
            }
        }
    }

    public function translateUrl($languageId, $originalUrl)
    {
        $translatedUrl = '';
        $segments = explode("/", str_replace("http://", "", $originalUrl));
        array_shift($segments);
        
        foreach($segments as $segment)
        {
            $cmd = "
            SELECT	title
            FROM	cmsArticleGroupText
            WHERE   languageId = " . $languageId . "
            AND     articleGroupId = (SELECT articleGroupId FROM cmsArticleGroupText AS cagt2 WHERE REPLACE(LOWER(cagt2.title), ' ', '-') = '" . $segment . "' AND cagt2.languageId <> " . $languageId . ") 
            ";
    
            $translatedSegment = $this->_database->getDatabase()->fetchOne($cmd);
            if(isset($translatedSegment) && strlen($translatedSegment) > 0)
            {
                $translatedUrl = $translatedUrl . "/" . $this->_cms->title2path($translatedSegment);
            }
            else
            {
                $cmd = "
                SELECT	title
                FROM	cmsArticleText
                WHERE   languageId = " . $languageId . "
                AND     articleId = (SELECT articleId FROM cmsArticleText AS cat2 WHERE REPLACE(LOWER(cat2.title), ' ', '-') = '" . $segment . "' AND cat2.languageId <> " . $languageId . ") 
                ";

                $translatedSegment = $this->_database->getDatabase()->fetchOne($cmd);
                if(isset($translatedSegment) && strlen($translatedSegment) > 0)
                {
                    $translatedUrl = $translatedUrl . "/" . $this->_cms->title2path($translatedSegment);
                }
                else
                {
                    $cmd = "
                    SELECT	title
                    FROM	cmsProductGroupText
                    WHERE   languageId = " . $languageId . "
                    AND     productGroupId = (SELECT productGroupId FROM cmsProductGroupText AS cpgt2 WHERE REPLACE(LOWER(cpgt2.title), ' ', '-') = '" . $segment . "' AND cpgt2.languageId <> " . $languageId . ") 
                    ";
    
                    $translatedSegment = $this->_database->getDatabase()->fetchOne($cmd);
                    if(isset($translatedSegment) && strlen($translatedSegment) > 0)
                    {
                        $translatedUrl = $translatedUrl . "/" . $this->_cms->title2path($translatedSegment);
                    }
                    else
                    {
                        $cmd = "
                        SELECT	title
                        FROM	cmsProductText
                        WHERE   languageId = " . $languageId . "
                        AND     productId = (SELECT productId FROM cmsProductText AS cpt2 WHERE REPLACE(LOWER(cpt2.title), ' ', '-') = '" . $segment . "' AND cpt2.languageId <> " . $languageId . ")
                        ";

                        $translatedSegment = $this->_database->getDatabase()->fetchOne($cmd);
                        if(isset($translatedSegment) && strlen($translatedSegment) > 0)
                        {
                            $translatedUrl = $translatedUrl . "/" . $this->_cms->title2path($translatedSegment);
                        }                        
                    }
                }
            }
        }

        if (preg_match("/^[\/a-zA-Z\-0-1]+$/", $translatedUrl))
            return strtolower($translatedUrl);
        else
            return "/";
    }
}

?>