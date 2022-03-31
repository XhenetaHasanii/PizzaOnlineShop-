<?php    // UTF-8 marker äöüÄÖÜß€
class OrderedPizza
{
    private $pizzaID = 0;
    private $orderID = 0;
    private $pizzaNumber = 0;
    private $status = 1;
    private $pizzaName = "N/A";


    public function __construct($m_pizzaID, $m_orderID, $m_pizzaNumber, $m_status, $m_pizzaName)
    {
        $this->pizzaID = $m_pizzaID;
        $this->orderID = $m_orderID;
        $this->pizzaNumber = $m_pizzaNumber;
        $this->status = $m_status;
        $this->pizzaName = $m_pizzaName;
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

    public function getPizzaNumber()
    {
        return $this->pizzaNumber;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusName($p_statusID)
    {
        switch ($p_statusID) {
            case 1:
                return "bestellt";
                break;
            case 2:
                return "ofen";
                break;
            case 3:
                return "fertig";
                break;
            case 4:
                return "unterwegs";
                    break;
            case 5:
                return "geliefert";
                break;
            default:
                echo "Undefinierter Status der Pizza";
                break;
        }
        return "fehler";
    }

    public function getPizzaName()
    {
        return $this->pizzaName;
    }

    private function comparator($object1, $object2)
    {
        return $object1->getOrderID() > $object2->getOrderID();
    }
}
