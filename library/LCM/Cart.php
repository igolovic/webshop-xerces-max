<?php

class CartItem
{
    protected $_id;
    protected $_price;
    protected $_title;
    protected $_quantity;
    protected $_totalPrice;

    public function __construct()
    {
        $this->productId = 0;
        $this->quantity = 0;
        $this->totalPrice = 0;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getPrice()
    {
        return $this->_price;
    }

    public function setPrice($price)
    {
        $this->_price = $price;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getQuantity()
    {
        return $this->_quantity;
    }

    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;
    }

    public function getTotalPrice()
    {
        return $this->_totalPrice;
    }

    public function setTotalPrice($totalPrice)
    {
        $this->_totalPrice = $totalPrice;
    }
}

class LCM_Cart
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

    public function addToCart($productId, $quantity)
    {
        $addNewItem = false;

        // cart is already created
        if (isset($_SESSION['cartItems']))
        {
            $this->_items = $_SESSION['cartItems'];

            // item already exists in cart
            if (isset($this->_items['id' . $productId]))
            {
                $cartItem = $this->_items['id' . $productId];
                $cartItem->setQuantity($quantity);
                $cartItem->setTotalPrice($quantity * $cartItem->getPrice());
                $this->_items['id' . $productId] = $cartItem;
            }
            // item is added to cart
            else
            {
                $addNewItem = true;
            }
        }
        // cart is not yet created
        else
        {
            $_SESSION['cartItems'] = array();
            $addNewItem = true;
        }

        if ($addNewItem)
        {
            $cmd = "
            SELECT      p.id, 
                        p.price, 
                        Ifnull(pt.title, '') AS title 
            FROM        cmsproduct           AS p 
            LEFT JOIN   cmsproducttext       AS pt 
            ON          p.id = pt.productid 
            WHERE       p.active = 1 
            AND         p.id =" . $productId;

            if ($row = $this
                ->_database
                ->getDatabase()
                ->fetchRow($cmd))
            {
                $ci = new CartItem;
                $ci->setId($row['id']);
                $ci->setPrice($row['price']);
                $ci->setTitle($row['title']);
                $ci->setQuantity($quantity);
                $ci->setTotalPrice($quantity * $row['price']);
                $this->_items['id' . $row['id']] = $ci;
            }
        }
        $_SESSION['cartItems'] = $this->_items;

        $cartItemCount = $cartValueTotal = 0;
        foreach ($this->_items as $ci)
        {
            $cartItemCount += $ci->getQuantity();
            $cartValueTotal += $ci->getTotalPrice();
        }
        return array(
            'cartItemCount' => $cartItemCount,
            'cartValueTotal' => $cartValueTotal
        );
    }

    public function removeFromCart($productId)
    {
        $cartItemCount = $cartValueTotal = 0;

        if (isset($_SESSION['cartItems']))
        {
            $this->_items = $_SESSION['cartItems'];
            if (isset($this->_items['id' . $productId]))
            {
                unset($this->_items['id' . $productId]);
                array_values($this->_items);
                foreach ($this->_items as $ci)
                {
                    $cartItemCount += $ci->getQuantity();
                    $cartValueTotal += $ci->getTotalPrice();
                }
                $_SESSION['cartItems'] = $this->_items;
            }
        }

        return array(
            'cartItemCount' => $cartItemCount,
            'cartValueTotal' => $cartValueTotal
        );
    }

    public function emptyCart()
    {
        $_SESSION['cartItems'] = null;
    }

    public function getCartItems()
    {
        return isset($_SESSION['cartItems']) ? $_SESSION['cartItems'] : array();
    }

    public function getCartTotals()
    {
        $cartItemCount = $cartValueTotal = 0;
        if (isset($_SESSION['cartItems']))
        {
            $this->_items = $_SESSION['cartItems'];

            foreach ($this->_items as $ci)
            {
                $cartItemCount += $ci->getQuantity();
                $cartValueTotal += $ci->getTotalPrice();
            }
        }

        return array(
            'cartItemCount' => $cartItemCount,
            'cartValueTotal' => $cartValueTotal
        );
    }
}

?>