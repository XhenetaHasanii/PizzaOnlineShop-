<?php    // UTF-8 marker äöüÄÖÜß€
class Delivery
{
    private $pizzaID = 0;
    private $orderID = 0;
    private $address = 0;
    private $orderTime;
    private $pizzaNumber = 0;
    private $status = "N/A";
    private $pizzaName = "N/A";
    private $price = "0.0";

    public function __construct($m_pizzaID, $m_orderID, $m_address, $m_orderTime, $m_pizzaNumber, $m_status, $m_pizzaName, $m_price)
    {
        $this->pizzaID = $m_pizzaID;
        $this->orderID = $m_orderID;
        $this->address = $m_address;
        $this->orderTime = $m_orderTime;
        $this->pizzaNumber = $m_pizzaNumber;
        $this->status = $m_status;
        $this->pizzaName = $m_pizzaName;
        $this->price = $m_price;
    }

    public function __destruct()
    {
    }

    public function getPizzaID()
    {
        return $this->pizzaID;
    }

    public function getOrderID()
    {
        return $this->orderID;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getBestellzeitpunkt()
    {
        return $this->orderTime;
    }

    public function getPizzaNummer()
    {
        return $this->pizzaNumber;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getPizzaName()
    {
        return $this->pizzaName;
    }

    public function getPrice()
    {
        return $this->price;
    }
}
