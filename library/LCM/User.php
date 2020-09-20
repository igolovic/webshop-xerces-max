<?php

class UserItem
{
    protected $_id;
    protected $_firstName;
    protected $_lastName;
    protected $_company;
    protected $_address;
    protected $_city;
    protected $_postalCode;
    protected $_state;
    protected $_phone;
    protected $_fax;
    protected $_email;
    protected $_admin;
    protected $_password;
    protected $_active;			

    public function getId()
    {
        return $this->_id;
    }
    
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    public function getFirstName()
    {
        return $this->_firstName;
    }
    
    public function setFirstName($firstName)
    {
        $this->_firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->_lastName;
    }
    
    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    public function getCompany()
    {
        return $this->_company;
    }
    
    public function setCompany($company)
    {
        $this->_company = $company;
    }
    
    public function getAddress()
    {
        return $this->_address;
    }
    
    public function setAddress($address)
    {
        $this->_address = $address;
    }
    
    public function getCity()
    {
        return $this->_city;
    }
    
    public function setCity($city)
    {
        $this->_city = $city;
    }

    public function getPostalCode()
    {
        return $this->_postalCode;
    }
    
    public function setPostalCode($postalCode)
    {
        $this->_postalCode = $postalCode;
    }

    public function getState()
    {
        return $this->_state;
    }
    
    public function setState($state)
    {
        $this->_state = $state;
    }

    public function getPhone()
    {
        return $this->_phone;
    }
    
    public function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    public function getFax()
    {
        return $this->_fax;
    }
    
    public function setFax($fax)
    {
        $this->_fax = $fax;
    }
    
    public function getEmail()
    {
        return $this->_email;
    }
    
    public function setEmail($email)
    {
        $this->_email = $email;
    }
    
    public function getAdmin()
    {
        return $this->_admin;
    }
    
    public function setAdmin($admin)
    {
        $this->_admin = $admin;
    }
	
    public function getPassword()
    {
        return $this->_password;
    }
    
    public function setPassword($password)
    {
        $this->_password = $password;
    }	
	
    public function getActive()
    {
        return $this->_active;
    }
    
    public function setActive($active)
    {
        $this->_active = $active;
    }
}

class LCM_User
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

    // get one or more items filtered by options
    public function fetchItems($optionsArray)
    {
		$cmd = "
		SELECT	id,
				firstName,
				lastName,
				company,
				address,
				city,
				postalCode,
				state,
				phone,
				fax,
				email,
				admin,
				password,
				active
		FROM	cmsUser";

        $conditionsArray = array();
		if(count($optionsArray) > 0)
		{    
			if(isset($optionsArray['id']))
			{
				$conditionsArray[] = 'id = ' . $optionsArray['id'];
			}
			
			if(isset($optionsArray['firstName']))
			{
				$conditionsArray[] .= 'firstName = ' . $optionsArray['firstName'];
			}
			
			if(isset($optionsArray['lastName']))
			{
				$conditionsArray[] .= 'lastName = ' . $optionsArray['lastName'];
			}
			
			if(isset($optionsArray['company']))
			{
				$conditionsArray[] .= "company = '" . $optionsArray['company'] . "'";
			}
			
			if(isset($optionsArray['address']))
			{
				$conditionsArray[] = "address = " . $optionsArray['address'];
			}
			
			if(isset($optionsArray['city']))
			{
				$conditionsArray[] = "city = '" . $optionsArray['city'] . "'";
			}
			
			if(isset($optionsArray['postalCode']))
			{
				$conditionsArray[] = "postalCode = '" . $optionsArray['postalCode'] . "'";
			}
			
			if(isset($optionsArray['state']))
			{
				$conditionsArray[] = "state = '" . $optionsArray['state'] . "'";
			}
			
			if(isset($optionsArray['phone']))
			{
				$conditionsArray[] = "phone = '" . $optionsArray['phone'] . "'";
			}
			
			if(isset($optionsArray['fax']))
			{
				$conditionsArray[] = "fax = '" . $optionsArray['fax'] . "'";
			}
			
			if(isset($optionsArray['email']))
			{
				$conditionsArray[] = "email = '" . $optionsArray['email'] . "'";
			}
			
			if(isset($optionsArray['admin']))
			{
				$conditionsArray[] = "admin = '" . $optionsArray['admin'] . "'";
			}
			
			if(isset($optionsArray['password']))
			{
				$conditionsArray[] = "password = '" . $optionsArray['password'] . "'";
			}			
			
			if(isset($optionsArray['active']))
			{
				$conditionsArray[] = " " . $optionsArray['active'] . " ";
			}
			
			if(count($conditionsArray) > 0)
			{
				$conditionsString = '';
				$conditionsString .= ' where ' . implode(' and ', $conditionsArray);
			}
			
			$cmd .= $conditionsString;
			
			if(isset($optionsArray['orderBy']))
			{
				$cmd .= " order by " . $optionsArray['orderBy'];
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
				$i = new UserItem;
				$i->setId($row['id']);
				$i->setFirstName($row['firstName']);
				$i->setLastName($row['lastName']);
				$i->setCompany($row['company']);
				$i->setAddress($row['address']);
				$i->setCity($row['city']);
				$i->setPostalCode($row['postalCode']);
				$i->setState($row['state']);
				$i->setPhone($row['phone']);
				$i->setFax($row['fax']);
				$i->setEmail($row['email']);
				$i->setAdmin($row['admin']);
				$i->setPassword($row['password']);
				$i->setActive($row['active']);
					
				$this->_items[] = $i;
			}
		}
	}

    public function save($userData)
    {    
        if(!isset($userData['id']))
        {
            $uniqueCustomerId = com_create_guid();
            $cmd = "
            INSERT INTO	cmsUser (id, firstname, lastname, company, address, city, postalcode, state, phone, fax, email, admin, password, active)
            VALUES		(" . $uniqueCustomerId . ", '"
						. $userData['firstname'] . "', '"
						. $userData['lastname']  . "', '"
						. $userData['company']  . "', '"
						. $userData['address']  . "', '"
						. $userData['city']  . "', '"
						. $userData['postalcode']  . "', '"
						. $userData['state']  . "', '"
						. $userData['phone']  . "', '"
						. $userData['fax']  . "', '"
						. $userData['email'] . "',
						0,
						'',
						1)";
        }
        
        $stmt = new Zend_Db_Statement_Mysqli($this->_database->getDatabase(), $cmd);
		
		if($stmt->execute())
        {
            return $uniqueCustomerId;
        }
        
		return null;
    }
	
	public function isUserAdmin($username, $password)
	{
		$cmd = "
		SELECT	IFNULL(id, -1)
		FROM	cmsUser
		WHERE	email = " . $username . "
				AND password = " . $password . "
				AND active = 1";
				
        $result = $this->_database->getDatabase()->fetchOne($cmd);
		
		if($result)
        {
            return true;
        }
        
		return false;	
	}
}

?>