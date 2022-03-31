<?php    // UTF-8 marker äöüÄÖÜß€


require_once './Page.php';
require_once './OrderedPizza.php';

class CustomerStatus extends Page
{

    private $orderNumber;
    protected function __construct()
    {
        parent::__construct();
        $this->orderNumber = 0;
        $this->orderArray = array();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData()
    {
        if (isset($_SESSION["order"]) && (is_numeric($_SESSION["order"]))) {
            $this->orderNumber = $_SESSION["order"];
        }
        $sqlStatement = "SELECT oa.id,oa.f_order_id,f_article_id,article.name, Status FROM ordered_articles oa JOIN article WHERE oa.f_order_id = $this->orderNumber AND oa.f_article_id = article.id";
        $recordSet = $this->_database->query($sqlStatement);

        while ($row = mysqli_fetch_assoc($recordSet)) {
            $orderArray[] = $row;
        }

        $recordSet->free();
        return $orderArray;
    }

    protected function generateView()
    {
        $tempArray = $this->getViewData();
        header("Content-Type: application/json; charset=UTF-8");
        $serializedData = json_encode($tempArray);
        echo ($serializedData);
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();
        header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 01 Jul 2000 06:00:00 GMT"); // Datum in der Vergangenheit
        header("Cache-Control: post-check=0, pre-check=0", false); // fuer IE
        header("Pragma: no-cache");
    }

    public static function main()
    {
        session_cache_limiter('nocache'); // VOR session_start()!
        session_cache_expire(0);
        session_start();
        try {
            $page = new CustomerStatus();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
        
    }
}

CustomerStatus::main();
