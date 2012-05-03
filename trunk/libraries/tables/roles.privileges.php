<?php

require_once("roles.actions.php");

class roles_privileges extends roles_actions {

    public $PrivilegeID;
    public $PrivilegeName;
    public $PrivilegeDescription;
    public $PrivilegeisSingular;
    public $mysql;

    public function __construct() {
        global $mysql;
        $this->mysql = $mysql;
    }

    public function convert_privilege_details($object) {
        $this->PrivilegeID = $object->ID;
        $this->PrivilegeName = $object->Name;
        $this->PrivilegeDescription = $object->Description;
        $this->PrivilegeisSingular = $object->isSingular;
    }

    public function get_privilege() {
        $sql = "
		SELECT * FROM roles_privileges where ID='{$this->PrivilegeID}'
		";
        $sql_query = $this->mysql->q($sql);
        if ($this->mysql->n($sql_query) > 0) {
            $sql_object = $this->mysql->fo($sql_query);
            $this->convert_privilege_details($sql_object);
            return true;
        }
        else
            return false;
    }

    public function get_privilege_actions() {
        $sql = "SELECT p.ID,p.Name AS PrivilegeName,a.Name AS `ActionName`,pa.ID AS ActionID,p.isSingular FROM {$GLOBALS["dbName"]}.roles_privileges_actions AS pa, {$GLOBALS["dbName"]}.roles_privileges AS p, {$GLOBALS["dbName"]}.roles_actions AS a
WHERE p.ID={$this->PrivilegeID} AND p.ID=pa.PrivilegeID AND a.ID=pa.ActionID ORDER BY p.ID ASC
		";

        return $this->mysql->q($sql);
    }

    public function has_action() {
        $sql = "SELECT p.ID,p.Name AS Privilege,a.Name AS `Action`,pa.ID AS ActionID,p.isSingular FROM {$GLOBALS["dbName"]}.roles_privileges_actions AS pa, {$GLOBALS["dbName"]}.roles_privileges AS p, {$GLOBALS["dbName"]}.roles_actions AS a
WHERE p.ID={$this->PrivilegeID} AND p.ID=pa.PrivilegeID AND a.Name='{$this->ActionName}' AND a.ID=pa.ActionID ORDER BY p.ID ASC";

        $sql_query = $this->mysql->q($sql);
        if ($this->mysql->n($sql_query) > 0)
            return true;
        else
            return false;
    }

    public function get_privileges() {
        $sql = "
		SELECT * FROM {$GLOBALS["dbName"]}.roles_privileges order by isSingular desc
		";
        return $this->mysql->q($sql);
    }

    public function get_privileges_actions() {
        $sql = "
		SELECT p.ID,p.Name AS PrivilegeName,a.Name AS `ActionName`,pa.ID AS ActionID,p.isSingular FROM {$GLOBALS["dbName"]}.roles_privileges_actions AS pa, {$GLOBALS["dbName"]}.roles_privileges AS p, {$GLOBALS["dbName"]}.roles_actions AS a
			WHERE p.ID=pa.PrivilegeID AND a.ID=pa.ActionID ORDER BY p.isSingular DESC,p.ID ASC,a.Name ASC
		";

        return $this->mysql->q($sql);
    }

    public function create_privilege() {
        $sql = "INSERT INTO {$GLOBALS["dbName"]}.roles_privileges (ID, `Name`, Description, isSingular) VALUES (NULL,'{$this->PrivilegeName}','{$this->PrivilegeDescription}','{$this->PrivilegeisSingular}');";
        $this->mysql->q($sql);
    }

    public function create_privilege_action() {
        $sql = "INSERT INTO {$GLOBALS["dbName"]}.roles_privileges_actions (ID, PrivilegeID, ActionID) VALUES (NULL, '{$this->PrivilegeID}','$this->ActionID');";
        $this->mysql->q($sql);
    }

    public function update_privilege() {
        
    }

}

?>