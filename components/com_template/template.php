<?php

class template {

    public $folder = '';
    public $default_folder = '';
    public $TemplateCache = '';
    public $CheckFile;
    public $compactor_mode;
    private $compactor;

    public function __construct($folder = "") {

        $this->folder = $this->default_folder . $folder;
        $this->CheckFile = new IO();
    }

    public function set_compactor_mode($value) {
        $this->compactor_mode = $value;
    }

    public function cache_all($templatesList, $Type="html") {
        $Tmp = explode(",", $templatesList);
        while (list($Key, $Val) = each($Tmp)) {
            $TmpFile = $this->folder . $Val . "." . $Type;
            $this->TemplateCache["$Val"] = $this->CheckFile->get_data($this->CheckFile->open($TmpFile), $TmpFile);
        }
        unset($Tmp);
        unset($TmpFile);
    }

    public function get($TemplateName, $Type="html") {

        if (isset($this->TemplateCache[$TemplateName])) {
            $Template = $this->TemplateCache[$TemplateName];
        } else {
            $TmpFile = $this->get_link($TemplateName, $Type);
            $Template = $this->CheckFile->get_data($this->CheckFile->open($TmpFile), $TmpFile);
            switch ($Type) {
                case "css":
                    $Template = "<style type=\"text/css\">" . $Template . "</style>";
                    break;
                case "javascript":
                case "js":
                    $Template = "<script type=\"text/javascript\">" . $Template . "</script>";
                    break;
                case "html":
                default:

                    break;
            }
            $this->TemplateCache[$TemplateName] = $Template;
        }

        $Template = addslashes($Template);
        $Template = str_replace("\'", "'", $Template);
        //$Template=("<!-- Console.Start ".$TemplateName." -->".$Template."<!-- Console.Close ".$TemplateName." -->");

        unset($TemplateName);
        unset($TmpFile);

        return $Template;
    }

// end func get 

    public function get_link($TemplateName, $Type="html") {
        switch ($Type) {
            case "css":
                $TmpFile = $this->folder . "/css/" . $TemplateName . ".css";
                break;
            case "javascript":
            case "js":
                $TmpFile = $this->folder . "/javascript/" . $TemplateName . ".js";
                break;
            case "html":
            default:
                $TmpFile = $this->folder . "/html/" . $TemplateName . ".html";
                break;
        }

        return $TmpFile;
    }

    public function get_js($TemplateName) {
        return "<script type=\"text/javascript\" src=\"" . $this->get_link($TemplateName, "javascript") . "\"></script>";
    }

    public function get_css($TemplateName) {
        return "<link href=\"" . $this->get_link($TemplateName, "css") . "\" rel=\"stylesheet\" type=\"text/css\" />";
    }

}

?>
