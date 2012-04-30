<?php

require_once("roles.php");

class account_roles extends roles {

    public $customer_id;
    public $role_id;
    public $mysql;

    public function __construct($action_name = NULL, $object_name = NULL) {
        global $mysql;
        $this->mysql = $mysql;
        $this->ActionName = $action_name;
        $this->ObjectName = $object_name;
    }

    public function convert_account_roles_details($object) {

        $this->customer_id = $object->customer_id;
        $this->role_id = $object->role_id;
    }
    
    

    /**
     * IsAllowedTo() checks whether a user is allowed to perform an action on a given object.
     * Return TRUE on success or FALSE on failure.
     *
     * If a user has two roles with the same importance. And each of those roles have the same action on an object but one is allowed and the other isn't.
     * The result will always be negative. I.e. is not allowed always wins when evertyhing else is equal.
     *
     * @access public
     * @param $id int, the user id
     * @param $action string, the action name
     * @param $object string, the object name
     * 
     *     
     */
    public function isAllowed() {
        $sql = "
    	SELECT isAllowed, t2.Name AS PrivilegeName, t2.isSingular AS PrivilegeisSingular, t4.Name AS ActionName, t5.Name AS DomainName, t5.isSingular AS DomainisSingular,
 t7.Name AS ObjectName, t8.Name AS RoleName, t8.Importance as RoleImportance
FROM {$GLOBALS["dbName"]}.roles_domains_privileges AS t1
				-- Privileges Joins --
				INNER JOIN {$GLOBALS["dbName"]}.roles_privileges AS t2 ON t2.id = t1.PrivilegeID
				INNER JOIN {$GLOBALS["dbName"]}.roles_privileges_actions AS t3 ON t3.PrivilegeID = t2.id
				INNER JOIN {$GLOBALS["dbName"]}.roles_actions AS t4 ON t4.id = t3.ActionID
				-- Domain Joins --
				INNER JOIN {$GLOBALS["dbName"]}.roles_domains AS t5 ON t5.id = t1.DomainID
				INNER JOIN {$GLOBALS["dbName"]}.roles_domains_objects AS t6 ON t6.DomainID = t5.id
				INNER JOIN {$GLOBALS["dbName"]}.roles_objects AS t7 ON t7.id = t6.ObjectID
				-- Roles to user Joins --
				INNER JOIN {$GLOBALS["dbName"]}.roles AS t8 ON t8.id = t1.RoleID
				INNER JOIN {$GLOBALS["dbName"]}.customers_roles AS t9 ON t9.role_id = t8.id
			WHERE customer_id = '{$this->customer_id}' AND t4.Name = '{$this->ActionName}' AND t7.Name = '{$this->ObjectName}'
			ORDER BY t8.Importance DESC, t8.Name
    	";

        $sql_query = $this->mysql->q($sql);
        if ($this->mysql->n($sql_query) > 0) {
            while ($object = $this->mysql->fo($sql_query)) {
                if ($object->isAllowed == "No")
                    return false;
            }
            return true;
        }
        else
            return false;
    }

    /**
     * get_account_privileges() Will load ALL the privileges associated with a user at once.
     *
     * @access public
     * @param $id int, the user id
     *
     * */
    public function get_account_privileges() {
        $sql = "
    	SELECT isAllowed, t2.Name AS PrivilegeName, t2.isSingular AS PrivilegeisSingular, t4.Name AS ActionName, t5.Name AS DomainName, t5.isSingular AS DomainisSingular,
 t7.Name AS ObjectName, t8.Name AS RoleName, t8.Importance as RoleImportance
FROM {$GLOBALS["dbName"]}.roles_domains_privileges AS t1
				-- Privileges Joins --
				INNER JOIN {$GLOBALS["dbName"]}.roles_privileges AS t2 ON t2.id = t1.PrivilegeID
				INNER JOIN {$GLOBALS["dbName"]}.roles_privileges_actions AS t3 ON t3.PrivilegeID = t2.id
				INNER JOIN {$GLOBALS["dbName"]}.roles_actions AS t4 ON t4.id = t3.ActionID
				-- Domain Joins --
				INNER JOIN {$GLOBALS["dbName"]}.roles_domains AS t5 ON t5.id = t1.DomainID
				INNER JOIN {$GLOBALS["dbName"]}.roles_domains_objects AS t6 ON t6.DomainID = t5.id
				INNER JOIN {$GLOBALS["dbName"]}.roles_objects AS t7 ON t7.id = t6.ObjectID
				-- Roles to user Joins --
				INNER JOIN {$GLOBALS["dbName"]}.roles AS t8 ON t8.id = t1.RoleID
				INNER JOIN {$GLOBALS["dbName"]}.customers_roles AS t9 ON t9.role_id = t8.id
			WHERE customer_id = '{$this->customer_id}'
			ORDER BY t8.Importance DESC, t8.Name
    	";

        $sql_query = $this->mysql->q($sql);
        return $sql_query;
    }

    public function get_account_role() {
        $sql = "SELECT a.* FROM {$GLOBALS["dbName"]}.`customers_roles` AS a, roles AS r WHERE a.customer_id='{$this->customer_id}' AND r.ID=a.role_id ORDER BY r.Importance DESC LIMIT 1";
        $sql_query = $this->mysql->q($sql);

        if ($this->mysql->n($sql_query) > 0) {
            $sql_object = $this->mysql->fo($sql_query);
            $this->convert_account_roles_details($sql_object);
            return true;
        }
        else
            return false;
    }

    public function get_account_roles() {
        $sql = "SELECT ar.customer_id, r.ID AS RoleID, r.Name AS RoleName, r.Description AS RoleDescription, r.Importance AS RoleImportance FROM {$GLOBALS["dbName"]}.roles AS r, customers_roles AS ar
WHERE ar.role_id=r.ID AND ar.customer_id='{$this->customer_id}' ORDER BY r.Importance DESC";
        return $this->mysql->q($sql);
    }

    public function get_accounts_roles() {
        $sql = "SELECT c.`email`,c.`name`,ar.customer_id,ar.role_id,r.Name AS RoleName,r.Importance AS RoleImportance 
                FROM {$GLOBALS["dbName"]}.customers_roles AS ar
                INNER JOIN {$GLOBALS["dbName"]}.roles AS r ON r.`ID` = ar.`role_id`
                INNER JOIN {$GLOBALS["dbName"]}.customers AS c ON c.`id` = ar.`customer_id`
                ORDER BY r.Importance DESC;";
        return $this->mysql->q($sql);
    }

    public function hasRole() {
        $sql = "SELECT r.* FROM {$GLOBALS["dbName"]}.roles AS r, customers_roles AS ar
WHERE ar.role_id=r.ID AND ar.customer_id='{$this->customer_id}' AND r.Name='{$this->RoleName}' LIMIT 1
         ";
        $sql_query = $this->mysql->q($sql);
        if ($this->mysql->n($sql_query) > 0) {
            return true;
        }
        else
            return false;
    }

    public function getRole() {
        $this->UserID = $this->customer_id;
        $this->get_account_role();
    }

    public function isAuthenticated() {
        global $CurrentSession;
        if (is_object($CurrentSession)) {
            $this->customer_id = $CurrentSession->id;
        }
        return $this->isAllowed();
    }

    public function create_account_role() {
        $sql = "INSERT INTO {$GLOBALS["dbName"]}.`customers_roles` (customer_id, role_id) VALUES ('{$this->customer_id}','{$this->role_id}');";
        $this->mysql->q($sql);
    }

    public function update_account_role() {
        $sql = "UPDATE {$GLOBALS["dbName"]}.customers_roles SET role_id='{$this->role_id}' WHERE customer_id='{$this->customer_id}'";
        $this->mysql->q($sql);
    }

}

?>