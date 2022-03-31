<?php    // UTF-8 marker äöüÄÖÜß€

abstract class Page
{

    protected $_database = null;

    protected function __construct()
    {
        error_reporting(E_ALL);

        $this->_database = new MySQLi("mariadb", "public", "public", "pizzaservice_2020");

        //$this->_database = new MySQLi("localhost", "public", "public", "pizzaservice_2020");

        if (mysqli_connect_errno())
            throw new Exception("Connect failed: " . mysqli_connect_error());

        // set charset to UTF8!!
        if (!$this->_database->set_charset("utf8"))
            throw new Exception($this->_database->error);
    }


    public function __destruct()
    {
        $this->_database->close();
    }

    protected function generatePageHeader($headline)
    {
        $headline = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");
        $title = $headline;
        echo "<!DOCTYPE html>";
        echo ("<html lang ='en'>");
        echo <<<EOT
        <!-- HEREDOC! Here comes the HTML Code -->
        <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css" />
        <link rel="icon" href="favicon.png" />
        <!--<meta http-equiv="refresh" content="5">-->
        <title>$title</title>
        EOT;
    }

    protected function generatePageFooter()
    {
        echo <<<EOT
        </body>
        </html>
        EOT;
    }

    protected function processReceivedData()
    {
    }
}
