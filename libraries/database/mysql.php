<?php
require_once("globalVariable.php");
class mysql {

    public $database = "";
    public $connection = 0;
    public $query_id = 0;
    public $record = array();
    public $errdesc = "";
    public $errno = 0;
    public $reporterror = 1;
    public $server = "localhost";
    public $user = "";
    private $password = "";
    public $appname = "";
    public $appshortname = "";
    public $errors = array();

    /**
     * structure the default database information
     * @return unknown_type
     */
    public function __construct() {
        $this->appname = "AgrdCore";
        $this->appshortname = 'AC';
        $this->server = "localhost";
        $this->user = "root";
        $this->password = "";
        $this->database = "";//setdb but it is not used,reference configuration
        $this->reporterror = 1;
    }
    
    public function set_password($password)
    {
        $this->password = $password;
    }

    /**
     * connect to database and select the database
     * @return unknown_type
     */
    public function connect() {
        if ($this->connection == 0) {
            if ($this->password == "") {
                $this->connection = mysql_connect($this->server, $this->user);
            } else {
                $this->connection = mysql_connect($this->server, $this->user, $this->password);
            }
        }
        if (!$this->connection) {
            $this->freeze("<br>Link-ID == MySQL , can not connect to your mysql ");
        }
        if ($this->database != "") {
            if (!mysql_select_db($this->database, $this->connection)) {
                $this->freeze("<br>can not select your database" . $this->database);
            }
        }
    }
    
    public function open()
    {
        $this->connect();
    }

    /**
     * get error description
     * @return unknown_type
     */
    private function geterrdesc() {
        $this->error = mysql_error();
        return $this->error;
    }

    /**
     * Get error no
     * @return unknown_type
     */
    private function geterrno() {
        $this->errno = mysql_errno();
        return $this->errno;
    }

    /**
     * Select the mysql database
     * @param $database
     * @return unknown_type
     */
    public function select_db($database="") {
        // select database
        if ($database != "") {
            $this->database = $database;
        }
        if (!mysql_select_db($this->database, $this->connection)) {
            $this->freeze("<br>Can not select your database" . $this->database);
        }
    }

    /**
     * do mysql_query and calculate the querytime and count the number of queries .
     * @param $query_string
     * @return unknown_type
     */
    public function q($query_string) {
        global $query_count, $showqueries, $querytime;
        if ($showqueries) {
            global $pagestarttime;
            $pageendtime = microtime();
            $starttime = explode(" ", $pagestarttime);
            $endtime = explode(" ", $pageendtime);

            $beforetime = $endtime['0'] - $starttime['0'] + $endtime['1'] - $starttime['1'];
        }
        $this->query_id = mysql_query($query_string, $this->connection);
        if (!$this->query_id) {
            $this->freeze("<br><b>Invalid SQL:</b> " . $query_string);
        }
        $query_count++;
        if ($showqueries) {
            $pageendtime = microtime();
            $starttime = explode(" ", $pagestarttime);
            $endtime = explode(" ", $pageendtime);

            $aftertime = $endtime['0'] - $starttime['0'] + $endtime['1'] - $starttime['1'];
            $querytime+=$aftertime - $beforetime;
        }
        return $this->query_id;
    }

    /**
     * Fetch array by the previous sql queries otherwise we use query string to fetch
     * @param $query_id
     * @param $query_string
     * @return unknown_type
     */
    public function f($query_id=-1, $query_string="") {
        if ($query_id != -1) {
            $this->query_id = $query_id;
        }

        if (isset($this->query_id)) {
            $this->record = mysql_fetch_array($this->query_id);
        } else {
            if (!empty($query_string)) {
                $this->freeze("<br>Invalid query id (" . $this->query_id . ") on this query: $query_string");
            } else {
                $this->freeze("<br>Invalid query id " . $this->query_id . " specified");
            }
        }

        return $this->record;
    }

    /**
     * Fetch object by the previous query if has otherwise we use query string to fetch
     * @param $query_id
     * @param $query_string
     * @return unknown_type
     */
    public function fo($query_id=-1, $query_string="") {
        if ($query_id != -1) {
            $this->query_id = $query_id;
        }

        if (isset($this->query_id)) {
            $this->record = mysql_fetch_object($this->query_id);
        } else {
            if (!empty($query_string)) {
                $this->freeze("<br>Invalid query id (" . $this->query_id . ") on this query: $query_string");
            } else {
                $this->freeze("<br>Invalid query id " . $this->query_id . " specified");
            }
        }

        return $this->record;
    }

    /**
     * Free the query result
     * @param $query_id
     * @return unknown_type
     */
    public function free($query_id=-1) {
        if ($query_id != -1) {
            $this->query_id = $query_id;
        }
        return @mysql_free_result($this->query_id);
    }

    /**
     * Fetch query array
     * @param $query_string
     * @return array
     */
    public function qf($query_string) {
        $query_id = $this->q($query_string);
        $returnarray = $this->f($query_id, $query_string);
        $this->free($query_id);
        return $returnarray;
    }

    /**
     * Fetch query objects
     * @param $query_string
     * @return object
     */
    public function qfo($query_string) {
        $query_id = $this->q($query_string);
        $returnobject = $this->fo($query_id, $query_string);
        $this->free($query_id);
        return $returnobject;
    }

    /**
     * Count number of rows
     * @param $query_id
     * @return unknown_type
     */
    public function n($query_id=-1) {
        if ($query_id != -1) {
            $this->query_id = $query_id;
        }
        return mysql_num_rows($this->query_id);
    }

    /**
     * RETURN AN ID OF THE PREVIOUS ROWS
     * @return unknown_type
     */
    public function iid() {
        return mysql_insert_id($this->connection);
    }

    /**
     * 
     * @return mixed
     */
    public function close() {
        return @mysql_close();
    }

    public function c() {
        return @mysql_close();
    }

    public function freeze($msg) {
        global $_SERVER;
        $this->errdesc = mysql_error();
        $this->errno = mysql_errno();
        
        $TechnicalEmail = "phamthelong@hotmail.com";
        $BBUserInfo = "AgrdCore";
        $ScriptPath = "";
        if ($this->reporterror == 1) {
            $Message = "<b>Database error in</b> " . $this->appname . " 1.0 beta:\n\n$msg\n";
            $Message.="<br><b>mysql error:</b> <font color=red> " . $this->errdesc . "</font>\n\n";
            $Message.="<br><b>mysql error number:</b><font color=green> " . $this->errno . "</font>\n\n";
            $Message.="<br><b>Date:</b> " . date("l dS of F Y h:i:s A") . "\n";
            $Message.="<br><b>Script:</b> $this->appname" . (($ScriptPath) ? $ScriptPath : $_SERVER['REQUEST_URI']) . "\n";
            $Message.="<br><b>Referer:</b> " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'none') . "\n";
            echo $Message;
            if ($TechnicalEmail) {
                @mail($TechnicalEmail, $this->appshortname . " Error Mysql!", "This is your error : \r\n $Message", "X-Priority: 1\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: Juidan Ho <$TechnicalEmail>\r\n");
            }
            echo "<html><head><title>DATABASE ERROR</title><style>P,BODY{FONT-FAMILY:tahoma,arial,sans-serif;FONT-SIZE:11px;}</style><body>\n\n<!-- $Message -->\n\n";
            echo "</table></td></tr></table></form>\n<blockquote><p>&nbsp;</p><p><b>There seems to have been a slight problem with the SaleStore's database.</b><br>\n";
            echo "Please try again by pressing the <a href=\"javascript:window.location=window.location;\">refresh</a> button in your browser.</p>";
            echo "An E-Mail has been dispatched to our <a href=\"mailto:pthelong@gmail.com\">Technical Staff</a>, who you can also contact if the problem persists.</p>";
            echo "<p>We apologise for any inconvenience.</p>";
            if ($BBUserInfo['usergroupid'] == 6) {
                echo "<form><textarea rows=\"12\" cols=\"60\">" . htmlspecialchars($Message) . "</textarea></form>";
            }
            echo "</blockquote></body></head></html>";
            exit;
        }
    }

}

?>