<?php    // UTF-8 marker äöüÄÖÜß€


require_once './Page.php';
require_once './Pizza.php';


class Order extends Page
{

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
        $pizzaCatalogue = array();
        $sqlStatement = "SELECT * FROM article";
        $recordSet = $this->_database->query($sqlStatement);

        if (!$recordSet) {
            throw new Exception("Error in Query: " . $this->_database->error);
        }

        while ($record = $recordSet->fetch_assoc()) {
            $number = $record['id'];
            $name = $record['name'];
            $path = $record['picture'];
            $price = $record['price'];

            $tempPizza = new Pizza($number, $name, $path, $price);
            array_push($pizzaCatalogue, $tempPizza);
        }
        $recordSet->free();
        return $pizzaCatalogue;
    }

    protected function generateView()
    {
        $catalogue = $this->getViewData();
        $this->generatePageHeader('Order');
        echo <<<EOT
        </head><body>
        <header><h1>Pizza Service - Your order</h1>
        <script src="OrderScript.js"> </script>
        EOT;
        echo <<<EOT
        </header>
        <div class="main">
            <h2>Menu</h2>
            <div class="pizzamenu">
        EOT;

        for ($i = 0; $i < sizeof($catalogue); ++$i) {
            $this->showPizzas($catalogue[$i]->getName(), $catalogue[$i]->getPath(), $catalogue[$i]->getPrice(), $i);
        }
        $selectSize = count($catalogue);
        
        echo <<<EOT
        </div>
        <section>
        <h2>Shopping Cart</h2>
        <div class="shoppingcart">
        <form id='choice' action = '#' method = 'post'>
        <select id = 'selectedPizzas' name = 'Choice[]' size = '$selectSize' tabindex = '1' multiple>
        </select>
        <div class="addressPrice">
        <input id = 'address' type = 'text' oninput ='checkSubmitButton()' onkeyup='checkSubmitButton2()' name= 'Address' placeholder = 'Your Address' value = ''>
        <p id ="totalPrice">0.00 €</p>
         
        </div>
        <div class="buttons">
        <input id = 'deleteSpecific' onclick = 'deleteSelectedPizzas()' form='choice' type = 'button' name = 'Choice delete' value = 'Delete selected Pizza' >
        <input id = 'deleteAll' form='choice' type = 'reset' onclick= 'deleteAllPizzas()' name = 'Delete all' value = 'Delete all' >
        <input id = 'sendOrder' onclick = 'selectPizzas()' type='submit' name = 'Order' value = 'Order' disabled ='disabled' >
        </div>
        
        </form>
        </div>
        </section>
        </div>
        EOT;

        $this->generatePageFooter();
    }

    public function showPizzas($pName, $pPath, $pPrice = 0.0, $idNum)
    {
        $piImg = "<img src='$pPath' " . "id='pizza" . "$idNum'" . " width='100' height='100' onclick='addToCart(this)' data-price='$pPrice' alt='$pName' title='$pName'>";
        $pArt = "<p>" . $pName . "</p>";
        $pPrice2 = "<p>" . number_format((float) $pPrice, 2) . " €</p>";
        echo ("<div class=article>");
        echo ($piImg);
        echo ($pArt);
        echo ($pPrice2);
        echo ("</div>");
    }

    public static function main()
    {
        session_start();
        try {
            $page = new Order();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function processReceivedData()
    {
        $tempArray = array();
        $tempAddress = "";
        $tempPizzaNumber = 0;
        $tempVar = "";
        parent::processReceivedData();
        if (isset($_POST["Choice"]) && (isset($_POST["Address"]))) {
            foreach ($_POST['Choice'] as $key => $value) {
                if (preg_match('/^[\-\p{L&} -]+$/u', $value)) {
                    $tempVar = $this->_database->real_escape_string($value);
                    array_push($tempArray, $tempVar);
                } else {
                    echo ("Choice is wrong");
                }
            }
            $tempAddress = $this->_database->real_escape_string($_POST["Address"]);

            $insertStatement1 = "INSERT INTO ordering (address) VALUES('$tempAddress')";
            if (!($this->_database->query($insertStatement1))) {
                echo "Error while creating the order";
            } else {
                $pizzaOrderID = $this->getLastOrderID();
                for ($k = 0; $k < sizeof($tempArray); ++$k) {
                    $tempPizzaNumber = $this->getPizzaNr($tempArray[$k]);
                    $insertStatement2 = "INSERT INTO ordered_articles(f_order_id,f_article_id,status) VALUES($pizzaOrderID,$tempPizzaNumber,1)";
                    $this->_database->query($insertStatement2);
                }
                $_SESSION["order"] = $pizzaOrderID;
                header('Location: http://localhost/PizzaShop/Customer.php');
            }
        }
    }

    public function getLastOrderID()
    {
        $tempStatement = " SELECT * FROM ordering ORDER BY id DESC LIMIT 1";
        $recordSet2 = $this->_database->query($tempStatement);

        if (!$recordSet2) {
            throw new Exception("Error in Query: " . $this->_database->error);
        }

        while ($record = $recordSet2->fetch_assoc()) {
            $lastID = $record['id'];
        }
        $recordSet2->free();
        return $lastID;
    }

    public function getPizzaNr($p_temp2)
    {
        $tempArr=$this->getViewData();
        for ($z = 0; $z < sizeof($tempArr); ++$z) {
            if ($p_temp2 == $tempArr[$z]->getName()) {
                return $tempArr[$z]->getNumber();
            }
        }
    }
}
Order::main();
