<?php

class pagination {

    public $page;
    public $url;
    public $start;
    public $limit;
    public $total;

    public function __construct($url,$page, $total, $limit) {
        $this->url = $url;
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
        $this->get_start_page();
    }

    function get_start_page() {
        if ($this->page < 1)
            $this->page = 1;
        $this->start = ($this->page - 1) * $this->limit;
    }

    public function get_html() {
        // $this->total, $this->limit, $this->url, $this->page = ''

        if ($this->page == '')
            $this->page = 1;

        if ($this->url == null)
            $this->url = "";

        $Page_block = "";
        $Page_num = @ceil($this->total / $this->limit);
        $StartPage = $this->page - 5;

        if ($StartPage < 1)
            $StartPage = 1;
        $LastPage = $StartPage + 9;

        if ($LastPage > $Page_num)
            $LastPage = $Page_num;
        if ($LastPage != $StartPage + 9)
            $StartPage = $LastPage - 9;
        if ($StartPage < 1)
            $StartPage = 1;

        if ($this->page == 1) {
            $PrePage = "";
            $First = "";
        } else {
            $PrePage = "";
            $First = "";
        }

        if ($this->page == $Page_num) {
            $NextPage = "<li><a class=\"next off\">Next</a></li>";
            $Last = "<li><a class=\"last off\">Last</a></li>";
        } else {
            $NextPage = "<li class=\"next\"><a href=\"" . $this->url . ($this->page + 1) . "\" rel=\"next\">Next</a></li>";
            $Last = "<li><a class=\"last\" href=\"" . $this->url . $Page_num . "\">Last </a></li>";
        }

        //$threadpagestats = "about $this->total results, max $this->limit results/page";

        while ($StartPage <= $LastPage) {
            if ($StartPage == $this->page) {
                $ShowPage = "<li><a class=\"active\">" . $StartPage . "</a></li>";
            } else if ($StartPage == 1) {
                $ShowPage = "<li><a href=\"" . $this->url . "\">" . $StartPage . "</a></li>";
            } else {
                $ShowPage = "<li><a href=\"" . $this->url . $StartPage . "\">" . $StartPage . "</a></li>";
            }

            $Page_block .= $ShowPage;
            $StartPage++;
        }

        $leftPage = $LastPage + 1;
        while ($leftPage <= 10) {

            $Page_block .= "<li><a class=\"off\">$leftPage</a></li>";
            $leftPage++;
        }


        $Page_block = "
        <ul id=\"pagination\">
                $First $PrePage $Page_block $NextPage $Last
        </ul>
        "; //$threadpagestats";
        return $Page_block;
    }

}

?>