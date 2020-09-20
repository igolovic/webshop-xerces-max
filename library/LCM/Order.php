<?php

class OrderRowItem
{
	protected $_id;
	protected $_orderId;	
	protected $_productId;
	protected $_productName;	
	protected $_productPrice;	
	protected $_productQuantity;
	protected $_productOrderValue;
		
	public function getId()
	{
		return $this->_id;
	}
	
	public function setId($id)
	{
		$this->_id = $id;
	}
	
	public function getOrderId()
	{
		return $this->_orderId;
	}
	
	public function setOrderId($orderId)
	{
		$this->_orderId = $orderId;
	}	
	
	public function getProductId()
	{
		return $this->_productId;
	}
	
	public function setProductName($productName)
	{
		$this->_productName = $productName;
	}
	
	public function getProductName()
	{
		return $this->_productName;
	}
	
	public function setProductId($productId)
	{
		$this->_productId = $productId;
	}	
	
	public function getProductPrice()
	{
		return $this->_productPrice;
	}
	
	public function setProductPrice($productPrice)
	{
		$this->_productPrice = $productPrice;
	}		
	
	public function getProductQuantity()
	{
		return $this->_productQuantity;
	}
	
	public function setProductQuantity($productQuantity)
	{
		$this->_productQuantity = $productQuantity;
	}
	
	public function getProductOrderValue()
	{
		return $this->_productOrderValue;
	}
	
	public function setProductOrderValue($productOrderValue)
	{
		$this->_productOrderValue = $productOrderValue;
	}
}

class OrderItem
{
    protected $_id;
    protected $_clientId;
    protected $_orderPaymentMethodTypeId;
    protected $_orderDate;
    protected $_orderPaymentMethodText;
	protected $_clientFirstNameLastName;
	protected $_clientCompany;
	protected $_clientDeliveryInfo;
	protected $_clientContactInfo;			
	protected $_orderRowItems;
	protected $_orderTotalValue;
	
    public function getId()
    {
        return $this->_id;
    }
	
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    public function getClientId()
    {
        return $this->_clientId;
    }
    
    public function setClientId($clientId)
    {
        $this->_clientId = $clientId;
    }
	    
    public function getOrderPaymentMethodTypeId()
    {
        return $this->_orderPaymentMethodTypeId;
    }

    public function setOrderPaymentMethodTypeId($orderPaymentMethodTypeId)
    {
        $this->_orderPaymentMethodTypeId = $orderPaymentMethodTypeId;
    }
		
    public function getOrderDate()
    {
        return $this->_orderDate;
    }
	
    public function setOrderDate($orderDate)
    {
        $this->_orderDate = $orderDate;
    }
	
    public function getOrderPaymentMethodText()
    {
        return $this->_orderPaymentMethodText;
    }
	
    public function setOrderPaymentMethodText($orderPaymentMethodText)
    {
        $this->_orderPaymentMethodText = $orderPaymentMethodText;
    }
	
	public function getClientFirstNameLastName()
	{
		return $this->_clientFirstNameLastName;
	}
	
	public function setClientFirstNameLastName($clientFirstNameLastName)
	{
		$this->_clientFirstNameLastName = $clientFirstNameLastName;
	}
	
	public function getClientCompany()
	{
		return $this->_clientCompany;
	}
	
	public function setClientCompany($clientCompany)
	{
		return $this->_clientCompany = $clientCompany;
	}	

	public function getClientDeliveryInfo()
	{
		return $this->_clientDeliveryInfo;
	}
	
	public function setClientDeliveryInfo($clientDeliveryInfo)
	{
		$this->_clientDeliveryInfo = $clientDeliveryInfo;
	}
	
	public function getClientContactInfo()
	{
		return $this->_clientContactInfo;
	}
	
	public function setClientContactInfo($clientContactInfo)
	{
		$this->_clientContactInfo = $clientContactInfo;
	}	
	
	public function getOrderRowItems()
	{
		return $this->_orderRowItems;
	}
	
	public function setOrderRowItems($orderRowItems)
	{
		$this->_orderRowItems = $orderRowItems;
	}
	
	public function addOrderRowItem($orderRowItem)
	{
		$this->_orderRowItems[] = $orderRowItem;
	}	
	
	public function getOrderTotalValue()
	{
		return $this->_orderTotalValue;
	}
	
	public function setOrderTotalValue($orderTotalValue)
	{
		$this->_orderTotalValue = $orderTotalValue;
	}	
}

class LCM_Order
{
    protected $_database;
    protected $_languageId;
    protected $_items;
	    
    public function __construct($database, $languageId)
    {
		$this->_languageId = $languageId;
		$this->_database = $database;
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
	
	public function deleteOrder($orderId)
	{
		$cmd = "
		DELETE FROM	cmsOrder
		WHERE		id = " . $orderId;

		$this->_database->getDatabase()->getConnection()->query($cmd);
	}
	
	public function fetchAllOrders()
	{
		// general order data
        $cmd = "
		SELECT		o.id,
					o.clientId,
					o.orderPaymentMethodTypeId,
					o.orderDate,
					opmt.text,
					CONCAT(u.firstName, ' ', u.lastName) AS clientFullName,
					u.company,
					CONCAT(u.state, ' ', u.city, ' ', u.postalCode, ' ', u.address) AS clientDeliveryInfo,
					CONCAT('E-mail: ', u.email, ' - Tel.:', u.phone, ' - Faks:', u.fax) AS clientContactInfo
		FROM		cmsOrder AS o
		INNER JOIN 	cmsOrderPaymentMethodText AS opmt ON
					o.orderPaymentMethodTypeId = opmt.orderPaymentMethodTypeId
					AND languageId = 1
		INNER JOIN	cmsUser AS u ON
					o.clientId = u.id
		ORDER BY	o.orderDate DESC";

        $rows = $this->_database->getDatabase()->fetchAll($cmd);
        $this->_items = array();
		
		if(count($rows) > 0)
		{
			foreach($rows as $row)
			{
                $i = new OrderItem;
				
				$i->setId($row['id']);
				$i->setClientId($row['clientId']);
				$i->setClientFirstNameLastName($row['clientFullName']);				
				$i->setClientCompany($row['company']);	
				$i->setClientDeliveryInfo($row['clientDeliveryInfo']);					
				$i->setClientContactInfo($row['clientContactInfo']);									
    			$i->setOrderPaymentMethodTypeId($row['orderPaymentMethodTypeId']);
    			$i->setOrderDate($row['orderDate']);
    			$i->setOrderPaymentMethodText($row['text']);
				
				// order details
				$cmd = "
				SELECT		_or.*,
							pt.title,
							p.price,
							(_or.quantity * p.price) AS productOrderValue
				FROM		cmsOrderRow AS _or
				INNER JOIN	cmsProductText AS pt ON
							_or.productId = pt.productId
							AND pt.languageId = 1
				INNER JOIN	cmsProduct AS p ON
							_or.productId = p.id							
				WHERE		_or.orderId = " . $row['id'];
				
				$rows2 = $this->_database->getDatabase()->fetchAll($cmd);
							
				foreach($rows2 as $row2)
				{
					$j = new OrderRowItem;
					$j->setId($row2['id']);
					$j->setOrderId($row2['orderId']);
					$j->setProductId($row2['productId']);
					$j->setProductName($row2['title']);					
					$j->setProductPrice($row2['price']);					
					$j->setProductQuantity($row2['quantity']);
					$j->setProductOrderValue($row2['productOrderValue']);
										
					$i->addOrderRowItem($j);
				}
				
				// total order value
				$cmd = "
				SELECT		SUM(_or.quantity * p.price) AS orderTotalValue
				FROM		cmsOrderRow AS _or
				INNER JOIN	cmsProduct AS p ON
						   	_or.productId = p.id
				WHERE  		orderId = " . $row['id'] . "
				GROUP BY 	orderId";
				
				$orderTotalValue = $this->_database->getDatabase()->fetchOne($cmd);				
				
				$i->setOrderTotalValue($orderTotalValue);
				
                $this->_items[] = $i;
			}
		}
		
		return $this->_items;
	}
    
    public function fetchPaymentMethods()
    {
        $cmd = "
		SELECT		opmty.id,
					opmte.text
		FROM		cmsOrderPaymentMethodType AS opmty
		INNER JOIN 	cmsOrderPaymentMethodText AS opmte ON
					opmty.id = opmte.orderPaymentMethodTypeId
					AND languageId = " . $this->_languageId;

		$paymentMethods=array();
        if($rows = $this->_database->getDatabase()->fetchAll($cmd))
		{
			foreach($rows as $row)
			{    
				$paymentMethods[] = array('pmId' => $row['id'], 'pmTitle' => $row['text']);
            }
        }
	
        return $paymentMethods;
    }

    public function saveRow($orderRowData)
    {
		if(!isset($orderRowData['id']))
		{
			$cmd = "
			INSERT INTO	cmsOrderRow (id, orderid, productid, quantity)
			VALUES		(NULL, 
						" . $orderRowData['orderid'] . ", 
						" . $orderRowData['productid'] . ", 
						"	. $orderRowData['quantity'] . ")";
		}

        $stmt = new Zend_Db_Statement_Mysqli($this->_database->getDatabase(), $cmd);
	
		if($stmt->execute())
		{
            return $this->_database->getDatabase()->lastInsertId();
        }
        
		return null;
    }
    
    public function save($orderData)
    {
		if(!isset($orderData['id']))
		{
				$randomOrderId = rand(10000, 99999);
			$cmd = "
			INSERT INTO cmsOrder(id, clientId, orderPaymentMethodTypeId, orderDate)
			VALUES
			(" . $randomOrderId . ","
			. $orderData['clientid'] . ", "
			. $orderData['paymentmethodtypeid'] . ",
			NOW())";
		}
	
        $stmt = new Zend_Db_Statement_Mysqli($this->_database->getDatabase(), $cmd);
	
		if($stmt->execute())
		{
            return $randomOrderId;
        }
        
		return null;
    }
}
  
?>