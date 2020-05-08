<?php
  /**
   * Page structure class
   * 2020-05-07
   * Chris Jokinen
   */

  require(QUERIES);

  class ClsPage extends ClsQueries{
    public function __construct($params){
      parent::__construct(...func_get_args());
    }

    public function loadHeader($pageName) {
      return file_get_contents('./lib/templates/header.html');
    }

    public function loadNaviagtion() {
      return file_get_contents('./lib/templates/navigation.html');
    }

    public function loadFooter($pageName) {
      return file_get_contents('./lib/templates/footer.html');
    }

    public function buildContent($pageName){
      $rtn = '';
      switch($pageName){
        case 'Employees':
          $rtn = $this->loadList($pageName);
          break;

        case 'Employee':
          $rtn = $this->loadEmployee($_GET['id']);
          break;

        case 'Departments':
          //$rtn = $this->loadList($pageName);
          break;

        case 'Department':
          //$rtn = $this->loadDepartment();
          break;

        case 'Report':
          $rtn = $this->loadReport();
          break;

        default:
      }
      return $rtn;
    }

    private function loadEmployee($id) {
      if( !isset($id) || empty($id) || !is_numeric($id) ) {
        header('Location: ./employees');
      }

      $msgs = [];
      if( isset($_POST) && !empty($_POST) ) {
        if( $this->isvalid('emp') ){ 
          $this->setEmplotee();
        }
        else{
          $msgs = $this->parseMessages($this->GetMessages());
        }
      }

      $rtn = '<form method="post">';
      $rslt = $this->getEmployee($id);
      if(count($rslt)>0){
        $row = $rslt[0];
        $rtn.= '<table class="editor">';
        $rtn.= '<tr><th>ID</th><td><input readonly name="id" value="'.$row['employee_id'].'" type="number">';
        if(array_key_exists('id', $msgs)) {
          $rtn.= '<div class="red">';
          foreach($msgs['id'] AS $m) {
            $rtn.= '<p>'.$m.'</p>';
          }
          $rtn.= '</div>';
        }
        $rtn.= '</td></tr>';
        $rtn.= '<tr><th>FIRST</th><td><input name="first" value="'.$row['first_name'].'">';
        if(array_key_exists('first', $msgs)) {
          $rtn.= '<div class="red">';
          foreach($msgs['first'] AS $m) {
            $rtn.= '<p>'.$m.'</p>';
          }
          $rtn.= '</div>';
        }
        $rtn.= '</td></tr>';
        $rtn.= '<tr><th>LAST</th><td><input name="last" value="'.$row['last_name'].'">';
        if(array_key_exists('last', $msgs)) {
          $rtn.= '<div class="red">';
          foreach($msgs['last'] AS $m) {
            $rtn.= '<p>'.$m.'</p>';
          }
          $rtn.= '</div>';
        }
        $rtn.= '</td></tr>';
        $rtn.= '<tr><th>DEPT</th><td>'.$this->makeOptions($row['departments'],$row['department_id']);
        if(array_key_exists('departments', $msgs)) {
          $rtn.= '<div class="red">';
          foreach($msgs['departments'] AS $m) {
            $rtn.= '<p>'.$m.'</p>';
          }
          $rtn.= '</div>';
        }
        $rtn.= '</td></tr>';
        $rtn.= '<tr><th>SALARY</th><td><input name="salary" value="'.$row['salary'].'" type="number">';
        if(array_key_exists('salary', $msgs)) {
          $rtn.= '<div class="red">';
          foreach($msgs['salary'] AS $m) {
            $rtn.= '<p>'.$m.'</p>';
          }
          $rtn.= '</div>';
        }
        $rtn.= '</td></tr>';
        $rtn.= '<tr><th>ADDRESS</th><td><input name="address" value="'.$row['street_address'].'">';
        if(array_key_exists('address', $msgs)) {
          $rtn.= '<div class="red">';
          foreach($msgs['address'] AS $m) {
            $rtn.= '<p>'.$m.'</p>';
          }
          $rtn.= '</div>';
        }
        $rtn.= '</td></tr>';
        $rtn.= '<tr><th>CITY</th><td><input name="city" value="'.$row['city'].'">';
        if(array_key_exists('city', $msgs)) {
          $rtn.= '<div class="red">';
          foreach($msgs['city'] AS $m) {
            $rtn.= '<p>'.$m.'</p>';
          }
          $rtn.= '</div>';
        }
        $rtn.= '</td></tr>';
        $rtn.= '<tr><th>STATE</th><td><input name="state" value="'.$row['state'].'">';
        if(array_key_exists('state', $msgs)) {
          $rtn.= '<div class="red">';
          foreach($msgs['state'] AS $m) {
            $rtn.= '<p>'.$m.'</p>';
          }
          $rtn.= '</div>';
        }
        $rtn.= '</td></tr>';
        $rtn.= '<tr><th>ZIP</th><td><input name="zip" value="'.$row['zip'].'" type="number" pattern="\b\d{5}\b">';
        if(array_key_exists('zip', $msgs)) {
          $rtn.= '<div class="red">';
          foreach($msgs['zip'] AS $m) {
            $rtn.= '<p>'.$m.'</p>';
          }
          $rtn.= '</div>';
        }
        $rtn.= '</td></tr>';
        $rtn.= '<tr><th>PHONE</th><td><input name="phone" value="'.$row['phone_number'].'" type="tel" pattern="^[\(]?(\d{3})[\)\-]?[\-]?(\d{3})[\- ]?(\d{4})$">';
        if(array_key_exists('phone', $msgs)) {
          $rtn.= '<div class="red">';
          foreach($msgs['phone'] AS $m) {
            $rtn.= '<p>'.$m.'</p>';
          }
          $rtn.= '</div>';
        }
        $rtn.= '</td></tr>';
        $rtn.= '<tr><td colspan="2" class="center red">* all field required</td></tr>';
        $rtn.= '<tr><td colspan="2" class="center"><input type="submit" value="Save"></td></tr>';
        $rtn.= '</table>';
      }
      else{
        $rtn.= 'Not today Satan!';
      }
      $rtn.= '</form>';
      return $rtn;
    }

    private function loadList($pageName) {
      $rtn = '<div class="emplist">';
      switch($pageName){
        case 'Departments':
          $rslt = [];
          break;
        default:
          $rslt = $this->getUsers();
          if(count($rslt)>0){
            $rtn.= '<table><tr>';
            $rtn.= '<th>ID</th>';
            $rtn.= '<th>FIRST</th>';
            $rtn.= '<th>LAST</th>';
            $rtn.= '<th>DEPT</th>';
            $rtn.= '<th>SALARY</th>';
            $rtn.= '<th>ADDRESS</th>';
            $rtn.= '<th>CITY</th>';
            $rtn.= '<th>STATE</th>';
            $rtn.= '<th>ZIP</th>';
            $rtn.= '<th>PHONE</th>';
            $rtn.= '</tr>';

            foreach($rslt AS $row) {
              $rtn.= '<tr id="'.$row['employee_id'].'"><td>'.$row['employee_id'].'</td>';
              $rtn.= '<td>'.$row['first_name'].'</td>';
              $rtn.= '<td>'.$row['last_name'].'</td>';
              $rtn.= '<td>'.$row['department'].'</td>';
              $rtn.= '<td>'.$row['salary'].'</td>';
              $rtn.= '<td>'.$row['street_address'].'</td>';
              $rtn.= '<td>'.$row['city'].'</td>';
              $rtn.= '<td>'.$row['state'].'</td>';
              $rtn.= '<td>'.$row['zip'].'</td>';
              $rtn.= '<td>'.$row['phone_number'].'</td></tr>';
            }
            $rtn.= '</table>';
            
          }
          else{
            $rtn.= 'Not today Satan!';
          }
          
      }
      $rtn.= '</div>';
      return $rtn;
    }

    // src = 1-Accounting|2-Marketing|3-Finance|4-HR
    private function makeOptions($src, $sel) {
      
      $s1 = explode("|",$src);
      $s2 = [];
      foreach ($s1 AS $s) {
        array_push($s2, explode("-",$s));
      }
      
      $rtn = '<select name="department">';
      foreach($s2 AS $opt){
        $rtn.= '<option value="'.$opt[0].'"';
        if($opt[0] == $sel) $rtn.= ' selected';
        $rtn.= '>'.$opt[1].'</option>';
      }
      $rtn.= '</select>';

      return $rtn;
    }

    private function isvalid($type) {
      $rtn = true;
      switch($type){
        case 'dept':
          break;
        default: // employee
          if($_GET['id']!=$_POST['id']) {
            $this->SetMessage('notice', 'You can not edit a different record', 'id', '');
            $rtn = $rtn & false;
          }
          $rtn = $rtn & $this->validString('first', $_POST['first'], true);
          $rtn = $rtn & $this->validString('last', $_POST['last'], true);

          $rtn = $rtn & $this->validNumber('department', $_POST['department'], true);
          $rtn = $rtn & $this->validNumber('salary', $_POST['salary'], true);

          $rtn = $rtn & $this->validString('address', $_POST['address'], true);
          $rtn = $rtn & $this->validString('city', $_POST['city'], true);
          $rtn = $rtn & $this->validString('state', $_POST['state'], true);

          $rtn = $rtn & $this->validZip('zip', $_POST['zip'], true);

          $rtn = $rtn & $this->validPhone('phone', $_POST['phone'], true);

          if($rtn) {
            $this->first         = $_POST['first'];
            $this->last          = $_POST['last'];
            $this->department    = $_POST['department'];
            $this->salary        = $_POST['salary'];
            $this->address       = $_POST['address'];
            $this->city          = $_POST['city'];
            $this->state         = $_POST['state'];
            $this->zip           = $_POST['zip'];
            $this->phone         = $_POST['phone'];
            $this->employee_id   = $_GET['id'];
          }
      }
      return $rtn;
    }

    private function validZip($ref, $in, $req) {
      $rtn = true;
      $pattern = '/\b\d{5}\b/';
      if (!preg_match($pattern, $in) ) {
        $this->SetMessage('notice', 'zip code requires 5 numbers', $ref, '');
        $rtn = $rtn & false;
      }
      return $rtn;
    }

    private function validPhone($ref, $in, $req) {
      $rtn = true;
      $pattern = '/^[\(]?(\d{3})[\)\-]?[\-]?(\d{3})[\- ]?(\d{4})$/';
      if (!preg_match($pattern, $in) ) {
        $this->SetMessage('notice', 'Phone number must be in the format of (xxx)-xxx-xxxx', $ref, '');
        $rtn = $rtn & false;
      }
      return $rtn;
    }

    private function validString($ref, $in, $req) {
      $rtn = true;
      if (strlen(trim($in))==0) {
        $this->SetMessage('notice', 'This field can not be empty', $ref, '');
        $rtn = $rtn & false;
      }
      
      return $rtn;
    }

    private function validNumber($ref, $in, $req) {
      $rtn = true;
      $pattern = '/\b\d{1,}\b/';
      if (!preg_match($pattern, $in) ) {
        $this->SetMessage('notice', 'This field requires a numeric value', $ref, '');
        $rtn = $rtn & false;
      }
      return $rtn;
    }

    private function parseMessages($msgs) {
      $rtn = [];
      foreach($msgs AS $m){
        if($m['lvl'] == 'notice') {
          $rtn[$m['fnct']][] = $m['msg'];
        }
      }
      return $rtn;
    }

    private function loadReport() {
      $rtn = "";
      $rslt = $this->getDepartmentSalaryTotals();
      if(count($rslt)>0){
        $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $rtn.= "<div><table><tr><th>Department</th><th>Total</th></tr>";
        foreach($rslt AS $row) {
          $rtn.= "<tr><td>".$row['department_name']."</td><td class='right'>".$formatter->formatCurrency($row['department_salary'], 'USD')."</td></tr>";
        }
        $rtn.= "</table></div>";
      }
      return $rtn;
    }
  }
?>