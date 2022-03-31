<?php    // UTF-8 marker äöüÄÖÜß€
class Pizza
{

    private $number = 0;
    private $name = "";
    private $path = "";
    private $price = 0.00;


    public function __construct($m_number, $m_name, $m_path, $m_price)
    {
        $this->number = $m_number;
        $this->name = $m_name;
        $this->path = $m_path;
        $this->price = $m_price;
    }

    public function __destruct()
    {
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getPrice()
    {
        return $this->price;
    }
}
