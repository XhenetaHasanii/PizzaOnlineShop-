<?php    // UTF-8 marker äöüÄÖÜß€


require_once './Page.php';
require_once './Delivery.php';

class Driver extends Page
{

    protected $_RadioID = 0;
    
    protected function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData()
    {
        $lieferAuftraege = array();
        $sqlStatement = "SELECT oa.id,oa.f_order_id,ordering.address,ordering.timestamp,oa.f_article_id,oa.status,article.name,article.price FROM ordered_articles as oa JOIN ordering,article WHERE oa.f_order_id = ordering.id AND oa.f_article_id=article.id AND oa.status < '5' ORDER BY ordering.id,oa.f_article_id;";
        $recordSet = $this->_database->query($sqlStatement);

        if (!$recordSet) {
            throw new Exception("Error in query: " . $this->_database->error);
        }

        while ($record = $recordSet->fetch_assoc()) {
            $pizzaID = $record['id'];
            $orderID = $record['f_order_id'];
            $adresse = $record['address'];
            $bestellzeitpunkt = $record['timestamp'];
            $pizzaNumber = $record['f_article_id'];
            $status = $record['status'];
            $pizzaName = $record['name'];
            $price = $record['price'];

            $adresse = htmlspecialchars($adresse);
            $delivery = new Delivery($pizzaID, $orderID, $adresse, $bestellzeitpunkt, $pizzaNumber, $status, $pizzaName, $price);
            $deliveryDuties[] = $delivery;
        }
        $recordSet->free();
        return $deliveryDuties;
    }

    protected function generateView()
    {
        $this->generatePageHeader('Delivery Service');
        echo <<<EOT
        <meta http-equiv="refresh" content="5">
        <script src="BestellungScript.js"></script>
        </head><body>
        <header><h1>Pizza Service - Delivery Service</h1></header>
        <div class="main"><h2>Auftr&auml;ge</h2>
        EOT;
        
        if(sizeof($this->getViewData())!=0){
            $deliveryDuties =  $this->getViewData();

            $tempBestellungID = $deliveryDuties[0]->getOrderID();
            $tempPizzaID = $deliveryDuties[0]->getPizzaID();
            $deliveryDuty = array();
            $anzPizzen = 0;


            for($i =0;$i<sizeof($deliveryDuties);$i++){
                if($deliveryDuties[$i]->getStatus()==3){
                    $anzPizzen++;
                }
            }
            
            echo ("<h3>To be delivered Pizzas: ".$anzPizzen."</h3>");
            echo ("<form id='fahrerForm' action='#' method='post'>");

            for ($i = 0; $i < sizeof($deliveryDuties); $i++) {
                if ($tempBestellungID == $deliveryDuties[$i]->getOrderID()) {
                    $deliveryDuty[] = $deliveryDuties[$i];
                } else {
                    $this->showDeliveryDuties($deliveryDuty);
                    $deliveryDuty = array();
                    $deliveryDuty[] = $deliveryDuties[$i];
                }

                $tempBestellungID = $deliveryDuties[$i]->getOrderID();
            }
            if(sizeof($deliveryDuty)!=0){
            $this->showDeliveryDuties($deliveryDuty);
            }
        }
        echo <<<EOT
        </form>
        </div>
        EOT;
        $this->generatePageFooter();
    }

    public function showDeliveryDuties($pDeliveryDuty)
    {
        $sDeliveryDuty = $pDeliveryDuty;
        $totalPrice = 0.00;
        $fortschritt = 0;
        $maxFortschritt = sizeof($sDeliveryDuty);
        $orderID = $sDeliveryDuty[0]->getOrderID();

        //Headline
        for ($i = 0; $i < sizeof($sDeliveryDuty); $i++) {
            $totalPrice = $totalPrice + $sDeliveryDuty[$i]->getPrice();
        }
        echo ("<div class='deliveryDuty'>");
        echo ("<div class='aAddress'>");
        echo ("<b>" . $sDeliveryDuty[0]->getAddress() . "    " . number_format($totalPrice, 2) . " €</b></div>");

        //Status

        echo ("<div class='radio-toolbar'>");

        $radioFinal = "";
        $radioBegin = "<input type ='radio' onclick='sendDriverForm();' id=rBtn" . $this->idCounter() . " name=" . $orderID . " value='";
        $radioChecked = "' checked='checked";
        $radioEnd = "'><span></span></label>";

        for ($k = 0; $k < sizeof($sDeliveryDuty); $k++) {
            $tempStatus = $sDeliveryDuty[$k]->getStatus();
            if ($tempStatus == "3") {
                $fortschritt = $fortschritt + (100 / $maxFortschritt);
                $fortschritt = round($fortschritt, 1);
                $radioFinal = "";
                $tempValue = $this->getStName($tempStatus);
                $radioFinal = "<label>baked" . $radioBegin . $tempValue . $radioChecked . $radioEnd;
                $radioBegin = "<input type ='radio' onclick='sendDriverForm();' id=rBtn" . $this->idCounter() . " name=" . $orderID . " value='";
                $radioFinal = $radioFinal . "<label>underway" . $radioBegin . "underway" . $radioEnd;
                $radioBegin = "<input type ='radio' onclick='sendDriverForm();' id=rBtn" . $this->idCounter() . " name=" . $orderID . " value='";
                $radioFinal = $radioFinal . "<label>delivered" . $radioBegin . "delivered" . $radioEnd;
            
            } elseif ($tempStatus == "4") {
                $fortschritt = 100;
                $radioFinal = "";
                $tempValue = $this->getStName($tempStatus);
                $radioFinal = "<label>baked" . $radioBegin . "baked" . $radioEnd;
                $radioBegin = "<input type ='radio' onclick='sendDriverForm();' id=rBtn" . $this->idCounter() . " name=" . $orderID . " value='";
                $radioFinal = $radioFinal . "<label>underway" . $radioBegin . $tempValue . $radioChecked . $radioEnd;
                $radioBegin = "<input type ='radio' onclick='sendDriverForm();' id=rBtn" . $this->idCounter() . " name=" . $orderID . " value='";
                $radioFinal = $radioFinal . "<label>delivered" . $radioBegin . "delivered" . $radioEnd;
            
            } elseif ($tempStatus == "5") {
                $fortschritt = 100;
                $radioFinal = "";
                $tempValue = $this->getStName($tempStatus);
                $radioFinal = "<label>baked" . $radioBegin . "baked" . $radioEnd;
                $radioBegin = "<input type ='radio' onclick='sendDriverForm();' id=rBtn" . $this->idCounter() . " name=" . $orderID . " value='";
                $radioFinal = $radioFinal . "<label>underway" . $radioBegin . "underway" . $radioEnd;
                $radioBegin = "<input type ='radio' onclick='sendDriverForm();' id=rBtn" . $this->idCounter() . " name=" . $orderID . " value='";
                $radioFinal = $radioFinal . "<label>delivered" . $radioBegin . $tempValue . $radioChecked . $radioEnd; 
            }
        }
        
        if (round($fortschritt,-1) >= 100) {
            echo ($radioFinal . "<br>");
            $fortschritt = 0;
        } else {
            echo ("Order is " . round($fortschritt, 0) . "% done.<br>");
            $fortschritt = 0;
        }
        echo ("</div>");

        //Pizza Aufzaehlung
        echo ("<div class='aPizzas'>");
        for ($j = 0; $j < sizeof($sDeliveryDuty); $j++) {
            if ($j == sizeof($sDeliveryDuty) - 1) {
                echo ($pDeliveryDuty[$j]->getPizzaName());
            } else {
                echo ($pDeliveryDuty[$j]->getPizzaName() . ", ");
            }
        }
        echo ("</div>");

        echo("</div>");
    }

    protected function idCounter(){
        $temp = $this->_RadioID;
        ++$this->_RadioID;
        return $temp;
    }

    protected function processReceivedData()
    {
        if(sizeof($this->getViewData())!=0){
            $tempArray = $this->getViewData();
            $minOrderID = $tempArray[0]->getOrderID();
            $maxOrderID = $this->getLastOrderID();
            $tempStatus = "";

            parent::processReceivedData();

            for ($i = $minOrderID; $i <= $maxOrderID; $i++) {
                if (isset($_POST[$i]) && ctype_alpha($_POST[$i])) {
                    $tempVar2 = $this->getStNumber($_POST[$i]);
                    $updateStatement = "UPDATE ordered_articles SET status = '" . $tempVar2 . "' WHERE ordered_articles.f_order_id='" . $i . "';";
                    $this->_database->query($updateStatement);
                }
            }
        }
    }

    public function getLastOrderID()
    {
        $sqlStatement = "SELECT * FROM ordered_articles WHERE f_order_id = (SELECT MAX(f_order_id) FROM ordered_articles)";
        $recordSet2 = $this->_database->query($sqlStatement);

        if (!$recordSet2) {
            throw new Exception("Error in Query: " . $this->_database->error);
        }

        while ($record = $recordSet2->fetch_assoc()) {
            $lastID = $record['f_order_id'];
        }
        $recordSet2->free();
        return $lastID;
    }

    public static function main()
    {
        try {
            $page = new Driver();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }

    private function getStName($p_temp)
    {
        switch ($p_temp) {
            case 1:
                return "ordered";
                break;
            case 2:
                return "furnace";
                break;
            case 3:
                return "baked";
                break;
            case 4:
                return "underway";
                break;
            case 5:
                return "delivered";
                break;
            default:
                echo "Undefined Status of the Pizza";
                break;
        }
        return "error";
    }

    private function getStNumber($p_temp2)
    {
        switch ($p_temp2) {
            case "ordered":
                return 1;
                break;
            case "furnace":
                return 2;
                break;
            case "baked":
                return 3;
                break;
            case "underway":
                return 4;
                break;
            case "delivered":
                return 5;
                break;
            default:
                echo "Undefined Status of the Pizza";
                break;
        }
        return -1;
    }
}

Driver::main();