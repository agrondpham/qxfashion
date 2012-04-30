<?php

require_once("roles.domains.php");
require_once("roles.privileges.php");

class roles_domains_privileges {

    public $ID;
    public $RoleID;
    public $DomainID;
    public $PrivilegeID;
    public $isAllowed;
    public $mysql;

    public function __construct() {
        global $mysql;
        $this->mysql = $mysql;
    }

    public function get_domains_privileges() {
        $sql = "SELECT r.Name AS RoleName, p.Name AS PrivilegeName, d.Name AS DomainName, dp.isAllowed,r.Importance FROM {$GLOBALS["dbName"]}.roles_domains_privileges AS dp, {$GLOBALS["dbName"]}.roles AS r, {$GLOBALS["dbName"]}.roles_domains AS d, {$GLOBALS["dbName"]}.roles_privileges AS p
				WHERE r.ID = dp.RoleID AND p.ID=dp.PrivilegeID AND d.ID=dp.DomainID";
        $sql_query = $this->mysql->q($sql);
        return $sql_query;
    }

    public function get_domains_privileges_by_roleid() {
        $sql = "SELECT * FROM {$GLOBALS["dbName"]}.roles_domains_privileges WHERE RoleID='{$this->RoleID}'";
        $sql_query = $this->mysql->q($sql);
        return $sql_query;
    }

    public function get_domains_privileges_by_domainid() {
        $sql = "SELECT * FROM {$GLOBALS["dbName"]}.roles_domains_privileges WHERE DomainID='{$this->RoleID}'";
        $sql_query = $this->mysql->q($sql);
        return $sql_query;
    }

    public function get_domains_privileges_by_privilegeid() {
        $sql = "SELECT * FROM {$GLOBALS["dbName"]}.roles_domains_privileges WHERE PrivilegeID='{$this->RoleID}'";
        $sql_query = $this->mysql->q($sql);
        return $sql_query;
    }

}

?>