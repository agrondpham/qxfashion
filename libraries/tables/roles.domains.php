<?php
require_once("roles.objects.php");
class roles_domains extends roles_objects
{
	public $DomainID;
	public $DomainName;
	public $DomainDescription;
	public $DomainisSingular;
	
	public $mysql;
	
	public function __construct()
	{
		global $mysql;
		$this->mysql = $mysql;
	}
	
	public function convert_domain_details($object)
	{
		$this->DomainID = $object->ID;
		$this->DomainName = $object->Name;
		$this->DomainDescription = $object->Description;
		$this->DomainisSingular = $object->isSingular;
	}

	
	public function get_domain()
	{
		$sql = "
			SELECT * FROM {$GLOBALS["dbName"]}.roles_domains WHERE ID={$this->DomainID}
		";
		$sql_query = $this->mysql->q($sql);
		if($this->mysql->n($sql_query) > 0)
		{
			$sql_object = $this->mysql->fo($sql_query);
			$this->convert_domain_details($sql_object);
			return true;			
		}
		else
			return false;
	}
	
	public function has_object_name()
	{
		$sql = "SELECT d.Name AS DomainName,o.Name AS ObjectName,d.isSingular FROM {$GLOBALS["dbName"]}.roles_domains_objects AS a,{$GLOBALS["dbName"]}.roles_domains AS d, {$GLOBALS["dbName"]}.roles_objects AS o
 			WHERE d.ID={$this->DomainID} AND d.ID=a.DomainID AND o.ID=a.ObjectID AND o.Name='{$this->ObjectName}'";
		$sql_query = $this->mysql->q($sql);
		if($this->mysql->n($sql_query) > 0)
		{
			return true;
		}
		else return false;
	}
	
	public function has_object()
	{
		$sql = "SELECT * FROM {$GLOBALS["dbName"]}.roles_domains_objects WHERE DomainID={$this->DomainID} AND ObjectID={$this->ObjectID}";
		$sql_query = $this->mysql->q($sql);
		if($this->mysql->n($sql_query) > 0)
			return true;
		else 
			return false;
	}
	
	public function get_domain_objects()
	{
		$sql = "SELECT d.Name AS DomainName,o.Name AS ObjectName,d.isSingular FROM {$GLOBALS["dbName"]}.roles_domains_objects AS a,{$GLOBALS["dbName"]}.roles_domains AS d, {$GLOBALS["dbName"]}.roles_objects AS o
 WHERE d.ID={$this->DomainID} AND d.ID=a.DomainID AND o.ID=a.ObjectID
		";
		$sql_query = $this->mysql->q($sql);
		return $this->mysql->q($sql_query);
	}
	
	
	public function get_domains()
	{
		$sql = "SELECT * FROM {$GLOBALS["dbName"]}.roles_domains order by isSingular desc";
		return $this->mysql->q($sql);
	}
	
	public function get_domains_objects()
	{
		$sql = "SELECT d.Name AS DomainName,o.Name AS ObjectName,d.isSingular FROM {$GLOBALS["dbName"]}.roles_domains_objects AS a,{$GLOBALS["dbName"]}.roles_domains AS d, {$GLOBALS["dbName"]}.roles_objects AS o
 				WHERE d.ID=a.DomainID AND o.ID=a.ObjectID ORDER BY d.Name ASC";
		return $this->mysql->q($sql);	
	}
	
	public function create_domain()
	{
		$sql = "
		INSERT INTO {$GLOBALS["dbName"]}.roles_domains (ID, `Name`, Description, isSingular) VALUES (NULL,'{$this->DomainName}','{$this->DomainDescription}','{$this->DomainisSingular}');
		";
		$this->mysql->q($sql);
	}
	
	public function create_domain_object()
	{
		$sql = "INSERT INTO {$GLOBALS["dbName"]}.roles_domains_objects (ID, DomainID, ObjectID) VALUES (NULL, '{$this->DomainID}', '{$this->ObjectID}');";
		$this->mysql->q($sql);
	}
	
	public function update_domain()
	{
		$sql = "UPDATE {$GLOBALS["dbName"]}.roles_domains SET `Name`='{$this->DomainName}',`Description`='{$this->DomainDescription}',`isSingular`='{$this->DomainisSingular}' WHERE ID='{$this->DomainID}'";
	}
}
?>