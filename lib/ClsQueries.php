<?php
  /**
   * Queries file
   * 2020-05-07
   * Chris Jokinen
   */

  require(DBCONN);

  class ClsQueries extends ClsDb {

    protected $first         = '';
    protected $last          = '';
    protected $department    = '';
    protected $salary        = '';
    protected $address       = '';
    protected $city          = '';
    protected $state         = '';
    protected $zip           = '';
    protected $phone         = '';
    protected $employee_id   = '';

    public function __construct($params){
      parent::__construct(...func_get_args());
    }

    protected function getUsers(){ 
      $sql = "SELECT
  employee_info.employee_id,
  employee_info.first_name,
  employee_info.last_name,
  (
    SELECT 
      department_info.department_name
    FROM
      department_info
    WHERE 
      department_info.department_id = employee_info.department_id
  ) AS department,
  employee_info.salary,
  employee_info.street_address,
  employee_info.city,
  employee_info.state,
  employee_info.zip,
  employee_info.phone_number
FROM
  employee_info";
      return $this->dbOutput($sql);
    }

    protected function getEmployee($id){
      $sql = "SELECT 
	employee_info.employee_id, 
	employee_info.first_name, 
	employee_info.last_name, 
	employee_info.department_id,
	(
		SELECT
			GROUP_CONCAT(dept SEPARATOR '|' )
		FROM
		(
			SELECT 
				1 as grp,
				CONCAT(department_id,'-',department_name) AS dept
			FROM 
				department_info
		) AS TBL
		GROUP BY grp	
	) AS departments, 
	employee_info.salary, 
	employee_info.street_address, 
	employee_info.city, 
	employee_info.state, 
	employee_info.zip, 
	employee_info.phone_number 
FROM 
	employee_info 
WHERE 
	employee_info.employee_id = :employee_id";
      $params = ['employee_id'=> $id];
      return $this->dbOutput($sql,$params);
    }

    protected function setEmplotee() {
      $sql = "UPDATE employee_info 
SET 
  first_name = :first, 
  last_name = :last, 
  department_id = :department,
  salary = :salary, 
  street_address = :address, 
  city = :city, 
  state = :state, 
  zip = :zip, 
  phone_number = :phone
WHERE 
  employee_id = :employee_id";
      $params = ['first' => $this->first, 'last' => $this->last, 'department' => $this->department, 'salary' => $this->salary, 'address' => $this->address, 'city' => $this->city, 'state' => $this->state, 'zip' => $this->zip, 'phone' => $this->phone,'employee_id'=> $this->employee_id];
      return $this->dbInput($sql, $params);
    }

    protected function getDepartmentSalaryTotals() {
      $sql = "SELECT
	department_name,
	(
		SELECT 
			SUM(employee_info.salary)
		FROM
			employee_info
		WHERE
			employee_info.department_id = department_info.department_id
		GROUP BY
			employee_info.department_id
	) AS department_salary
FROM
	department_info
ORDER BY
	department_name";
      return $this->dbOutput($sql);
    }
  }
?>