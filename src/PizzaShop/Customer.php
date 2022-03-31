<?php    // UTF-8 marker äöüÄÖÜß€


require_once './Page.php';
require_once './OrderedPizza.php';

class Customer extends Page
{
    private $radioGroupNr;
    private $myOrder;

    protected $_RadioID = 1;

    protected function __construct()
    {
        parent::__construct();
        $radioGroupNr = 0;
        $myOrder = 0;
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData()
    {
        $tempOrders = array();
        $sqlStatement = "SELECT oa.id,oa.f_order_id,oa.f_article_id,article.name,oa.status FROM ordered_articles as oa JOIN article WHERE oa.f_article_id = article.id AND oa.f_order_id = '$this->myOrder'";
        $recordSet = $this->_database->query($sqlStatement);
        if (!$recordSet) {
            throw new Exception("Error in query: " . $this->_database->error);
        }

        while ($record = $recordSet->fetch_assoc()) {
            $pPizzaID = $record['id'];
            $pfOrderID = $record['f_order_id'];
            $pfPizzaNumber = $record['f_article_id'];
            $pPizzaName = $record['name'];
            $pStatus = $record['status'];

            $tempPizza2 = new OrderedPizza($pPizzaID, $pfOrderID, $pfPizzaNumber, $pStatus, $pPizzaName);
            $tempOrders[]=$tempPizza2;
        }
        $recordSet->free();
        return $tempOrders;
    }

    protected function generateView()
    {
        $this->generatePageHeader('Customer Site');
        $orderOverview = $this->getViewData();
        echo <<<EOT
        <script src="CustomerScript.js"></script>
        </head>
        <body id="body" onload="customerScriptStart();">
        <header><h1>Overview of all orders</h1></header>
        <div class="main">
        EOT;

        for ($i = 0; $i < sizeof($orderOverview); ++$i) {
            $this->showOrderedPizzas($orderOverview[$i]->getPizzaID(), $orderOverview[$i]->getOrderID(), $orderOverview[$i]->getPizzaName(), $orderOverview[$i]->getStatus());
        }

        echo("</div>");
        $this->generatePageFooter();
    }

    public function showOrderedPizzas($mPizzaID, $mOrderId, $mName, $mStatus)
    {
        $availableStatus = array(1 => "ordered", 2 => "furnace", 3 => "backed", 4 => "underway", 5 => "delivered");
        $order = "<p> Order " . $mOrderId . ": " . $mName . "</p>";

        echo ($order);
        for ($j = 1; $j < sizeof($availableStatus) + 1; ++$j) {
            $radioID = $mOrderId.$mPizzaID.$j;
            if ($j == $mStatus) {
                echo ("<label>$availableStatus[$j]<input type = 'radio' id=rBtn" . $this->idCounter($mPizzaID) . " name ='customerstatus$this->radioGroupNr' value = '$availableStatus[$j]' checked='checked'></label>" . "<br>");
            } else {
                echo ("<label>$availableStatus[$j]<input type = 'radio' id=rBtn" . $this->idCounter($mPizzaID) . " name ='customerstatus$this->radioGroupNr' value = '$availableStatus[$j]'></label>" . "<br>");
            }
        }
        ++$this->radioGroupNr;
    }

    protected function idCounter($mPizzaID){
        $temp = "N/A";
        if($this->_RadioID >=6){
            $this->_RadioID=1;
        }
        $temp = $mPizzaID . "-" . $this->_RadioID;
        ++$this->_RadioID;
        
        return $temp;
    }

    protected function processReceivedData()
    {
        if (isset($_SESSION["order"]) && (is_numeric($_SESSION["order"]))) {
            $this->myOrder = $_SESSION["order"];
        } else {
            echo ("Session not initialised");
        }
        parent::processReceivedData();
    }

    public static function main()
    {
        session_start();
        try {
            $page = new Customer();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }

    private function comparator($object1, $object2)
    {
        return $object1->getOrderID() > $object2->getOrderID();
    }
}

Customer::main();