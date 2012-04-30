<?php
require_once("roles.domains.privileges.php");
class roles extends roles_domains_privileges
{
	public $RoleID;
	public $RoleName;
	public $RoleDescription;
	public $RoleImportance;
	
	public $mysql;
	
	public function __construct()
	{
		global $mysql;
		$this->mysql = $mysql;
	}
	
	public function convert_role_details($object)
	{
		$this->RoleID = $object->ID;
		$this->RoleName = $object->Name;
		$this->RoleDescription = $object->Description;
		$this->RoleImportance = $object->Importance;
	}
	
	public function get_role()
	{
		$sql = "SELECT * FROM {$GLOBALS["dbName"]}.roles WHERE ID={$this->RoleID}";
		$sql_query = $this->mysql->q($sql);
		if($this->mysql->n($sql_query) > 0)
		{
			$sql_object = $this->mysql->fo($sql_query);
			$this->convert_role_details($sql_object);
			return true;
		}
		else return false;
	}
	
	public function get_role_permissions_by_rolename()
	{
		$sql = "SELECT r.Name AS RoleName, p.Name AS Privilege, d.Name AS Domain, dp.isAllowed,r.Importance FROM {$GLOBALS["dbName"]}.roles_domains_privileges AS dp, {$GLOBALS["dbName"]}.roles AS r, {$GLOBALS["dbName"]}.roles_domains AS d, {$GLOBALS["dbName"]}.roles_privileges AS p
				WHERE r.Name='{$this->RoleName}' AND r.ID = dp.RoleID AND p.ID=dp.PrivilegeID AND d.ID=dp.DomainID
		";
		
		return $this->mysql->q($sql);
	}	
	
	public function get_roles()
	{
		$sql = "SELECT ID as RoleID,`Name` as RoleName,Description as RoleDescription,Importance as RoleImportance FROM {$GLOBALS["dbName"]}.roles ORDER BY Importance DESC";
		$sql_query = $this->mysql->q($sql);
		return $sql_query;
	}
	
	public function get_all_roles_permissions()
	{
		$sql = "SELECT r.Name AS RoleName, p.Name AS Privilege, d.Name AS Domain, dp.isAllowed,r.Importance FROM {$GLOBALS["dbName"]}.roles_domains_privileges AS dp, {$GLOBALS["dbName"]}.roles AS r, {$GLOBALS["dbName"]}.roles_domains AS d, {$GLOBALS["dbName"]}.roles_privileges AS p
				WHERE r.ID = dp.RoleID AND p.ID=dp.PrivilegeID AND d.ID=dp.DomainID
		";
		
		return $this->mysql->q($sql);
	}

	public function create_role()
	{
		$sql = "INSERT INTO {$GLOBALS["dbName"]}.roles (ID, `Name`, Description, Importance) VALUES (NULL, '{$this->RoleName}','{$this->RoleDescription}','{$this->RoleImportance}');";
		$this->mysql->q($sql);
	}
	
	public function create_domain_privilege()
	{
		$sql = "INSERT INTO {$GLOBALS["dbName"]}.roles_domains_privileges (ID, RoleID, PrivilegeID, DomainID, isAllowed) VALUES
(NULL,'{$this->RoleID}','{$this->PrivilegeID}','{$this->DomainID}','{$this->isAllowed}');";
		$this->mysql->q($sql);
	}
	
	
	
}
?>