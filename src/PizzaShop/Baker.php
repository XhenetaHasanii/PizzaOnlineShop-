<?php    // UTF-8 marker äöüÄÖÜß€
require_once './Page.php';
require_once './OrderedPizza.php';

class Baker extends Page
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

        $bakingDuties = array();
        $sqlStatement = "SELECT oa.id,oa.f_order_id,oa.f_article_id,oa.status,article.name FROM ordered_articles as oa JOIN article WHERE oa.f_article_id = article.id AND (oa.status = 1 OR oa.status = 2 OR oa.status = 3) ORDER BY oa.f_order_id,oa.f_article_id";
        $recordSet = $this->_database->query($sqlStatement);

        if (!$recordSet) {
            throw new Exception("Error in query: " . $this->_database->error);
        }

        while ($record = $recordSet->fetch_assoc()) {
            $pizzaID = $record['id'];
            $orderID = $record['f_order_id'];
            $pizzaNumber = $record['f_article_id'];
            $status = $record['status'];
            $pizzaName = $record['name'];

            $BP = new OrderedPizza($pizzaID, $orderID, $pizzaNumber, $status, $pizzaName);
            array_push($bakingDuties, $BP);
        }
        $recordSet->free();
        return $bakingDuties;
    }

    protected function generateView()
    {
        $this->generatePageHeader('Backing Furnace');
        echo <<<EOT
        <meta http-equiv="refresh" content="5">
        <script src="OrderScript.js"></script>
        </head>
        <body>
        <header><h1>Pizza Service - Backing Furnace</h1></header>
        <div class="main"><h2>Backing Duties</h2>
        <form id="bakerForm" action='#' method='post'>
        <table><tr><th> </th> <th>Ordered</th> <th>Furnace</th> <th>Baked</th> </tr>
        EOT;

        $bakingDuties = $this->getViewData();
        
        
        for ($i = 0; $i < sizeof($bakingDuties); ++$i) {
            $radioStelle = $i;
            $this->showDuties(
                $radioStelle,
                $bakingDuties[$i]->getPizzaName(),
                $bakingDuties[$i]->getStatus()
            );
        }

        echo <<<EOT
        </table>
        <br>
        </form>
        </div>
        EOT;

        $this->generatePageFooter();
    }

    public function showDuties($pRadioStelle, $pPizzaName, $pStatus)
    {
        $temp = $pRadioStelle;

        if ($pStatus == 1) {
            echo "<tr><td>" . $pPizzaName . "</td>";
            $tempVar = $this->getStName($pStatus);
            $pizzaRadio = "";
            $pizzaRadio = "<td><label><input type='radio' id=rBtn$this->_RadioID name=$temp value=$tempVar onclick='sendBakerForm();' checked='checked'></label></td>";
            ++$this->_RadioID;
            $pizzaRadio = $pizzaRadio . "<td><label><input type='radio' id=rBtn$this->_RadioID name=$temp onclick='sendBakerForm();' value='furnace'></label></td>";
            ++$this->_RadioID;
            $pizzaRadio = $pizzaRadio . "<td><label><input type='radio' id=rBtn$this->_RadioID name=$temp onclick='sendBakerForm();' value='baked'></label></td>";
            ++$this->_RadioID;
            echo ($pizzaRadio);
        } else if ($pStatus == 2) {
            echo "<tr><td>" . $pPizzaName . "</td>";
            $tempVar = $this->getStName($pStatus);
            $pizzaRadio = "";
            $pizzaRadio = "<td><label><input type='radio' id=rBtn$this->_RadioID name=$temp onclick='sendBakerForm();' value='ordered'></label></td>";
            ++$this->_RadioID;
            $pizzaRadio = $pizzaRadio . "<td><label><input type='radio' id=rBtn$this->_RadioID name=$temp value=$tempVar onclick='sendBakerForm();' checked='checked'></label></td>";
            ++$this->_RadioID;
            $pizzaRadio = $pizzaRadio . "<td><label><input type='radio' id=rBtn$this->_RadioID name=$temp onclick='sendBakerForm();' value='baked'></label></td>";
            ++$this->_RadioID;
            echo ($pizzaRadio);
        } else if ($pStatus == 3) {
            echo "<tr><td>" . $pPizzaName . "</td>";
            $tempVar = $this->getStName($pStatus);
            $pizzaRadio = "";
            $pizzaRadio = "<td><label><input type='radio' id=rBtn$this->_RadioID name=$temp onclick='sendBakerForm();' value='ordered'></label></td>";
            ++$this->_RadioID;
            $pizzaRadio = $pizzaRadio . "<td><label><input type='radio' id=rBtn$this->_RadioID name=$temp onclick='sendBakerForm();' value='furnace'></label></td>";
            ++$this->_RadioID;
            $pizzaRadio = $pizzaRadio . "<td><label><input type='radio' id=rBtn$this->_RadioID name=$temp value=$tempVar onclick='sendBakerForm();' checked='checked'></label></td>";
            ++$this->_RadioID;
            echo ($pizzaRadio);
        }
        echo "</tr>";
    }

    public static function main()
    {
        try {
            $page = new Baker();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function processReceivedData()
    {

        $anzAuftraege = $this->getNumberOfBakingDuties();
        $tempAuftraege = $this->getViewData();
        parent::processReceivedData();

        if ($anzAuftraege > 0) {
            for ($i = 0; $i <= $anzAuftraege; $i++) {
                if (isset($_POST[$i]) && ctype_alpha($_POST[$i])) {
                    $tempVar2 = $this->getStNumber($_POST[$i]);
                    $sqlUpdate = "UPDATE ordered_articles SET status='" . $tempVar2 . "' WHERE ordered_articles.id='" . $tempAuftraege[$i]->getPizzaID() . "';";
                    if (!($this->_database->query($sqlUpdate))) {
                        echo "Error while updating the status of the orders";
                    }
                }
            }
        }
    }

    public function getNumberOfBakingDuties()
    {
        $sqlStatement = "SELECT COUNT(status) FROM ordered_articles WHERE ordered_articles.status = 1 OR ordered_articles.status = 2 OR ordered_articles.status = 3";
        $recordSet2 = $this->_database->query($sqlStatement);

        if (!$recordSet2) {
            throw new Exception("Error in Query: " . $this->_database->error);
        }

        while ($record = $recordSet2->fetch_assoc()) {
            $anzahl = $record['COUNT(status)'];
        }
        $recordSet2->free();
        return $anzahl;
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
        return "fehler";
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

Baker::main();