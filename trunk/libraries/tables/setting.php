<?php

class setting {

    protected $self = array();
    private $array = array();
    public $mysql;
    public $ID;
    public $GroupID;
    public $Name;
    public $Type;
    public $Value;
    public $Title;
    public $Description;
    public $OptionCode;
    public $Status;
    private $display_variable;

    public function __construct($display = false) {
        global $mysql;
        $this->mysql = $mysql;
        $this->display_variable = $display;
        $this->get_variables();
    }

    public function get_detail($object) {
        $this->ID = $object->ID;
        $this->GroupID = $object->GroupID;
        $this->Name = $object->Name;
        $this->Type = $object->Type;
        $this->Value = $object->Value;
        $this->Title = $object->Title;
        $this->Description = $object->Description;
        $this->OptionCode = $object->OptionCode;
        $this->Status = $object->Status;
    }

    public function __get(/* string */ $name = null) {
        return $this->self[$name];
    }

    private function add_enum(/* string */ $name = null, /* int */ $enum = null) {
        if (isset($enum))
            $this->self[$name] = $enum;
        else
            $this->self[$name] = end($this->self) + 1;
    }

    public function create_variable() {
        $this->mysql->q("insert into {$GLOBALS["dbName"]}.`settings` ( `GroupID`, `Name`, `Type`, `Value`, `Title`, `Description` ) VALUES (
        '" . $this->GroupID . "','" . $this->Name . "','" . $this->Type . "','" . $this->Value . "','" . $this->Title . "','" . $this->Description . "'
        );");
        $this->ID = $this->mysql->iid();
    }

    public function get_variable() {
        return $this->mysql->qfo("select * from {$GLOBALS["dbName"]}.`settings` where `ID`='" . $this->ID . "'");
    }

    public function update_variable() {
        $sql = "update {$GLOBALS["dbName"]}.`settings` set `Status`='" . $this->Status . "'";
        if (isset($this->Name))
            $sql .= ", `Name`='" . $this->Name . "'";
        if (isset($this->Description))
            $sql .= ", Description = '" . $this->Description . "'";
        if (isset($this->GroupID))
            $sql .= ", GroupID = '" . $this->GroupID . "'";
        if (isset($this->Type))
            $sql .= ", `Type` = '" . $this->Type . "'";
        if (isset($this->Value))
            $sql .= ", `Value` = '" . $this->Value . "'";
        if (isset($this->Title))
            $sql .= ", `Title` = '" . $this->Title . "'";
        if ($isset($this->OptionCode))
            $sql .= ", `OptionCode` '" . $this->OptionCode . "'";
        $sql .= " where `ID`='" . $this->ID . "'";
        $this->mysql->q($sql);
    }

    public function get_variables_group() {
        return $this->mysql->q("select * from {$GLOBALS["dbName"]}.`settings` where GroupID = '" . $this->GroupID . "'");
    }

    public function delete() {
        $this->mysql->q("update {$GLOBALS["dbName"]}.`settings` set `Status`='" . $this->Status . "' where `ID`='" . $this->ID . "'");
    }

    private function get_variables() {
        $this->array = array();
        $system_query = $this->mysql->q("select * from {$GLOBALS["dbName"]}.`settings`");
        while ($system = $this->mysql->f($system_query)) {

            switch ($system['Type']) {
                case "array":
                    $kewqa = explode("[", $system['Name']);
                    $kewq = str_replace("]", "", $kewqa[1]);
                    $vaewis = $kewqa[0];
                    $this->array[$kewq] = $system['Value'];
                    break;
                case "bool":
                case "integer":
                case "string":
                case "double":
                default:
                    $this->add_enum($system['Name'], $system['Value']);
            }

            if ($this->display_variable)
                echo "<div>Variable name \"" . $system['Name'] . "\"=\"" . $system['Value'] . "\"</div>";
        }
    }

}

?>