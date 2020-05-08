<?php
  /**
   * DB file
   * 2020-05-07
   * Chris Jokinen
   */
  
  require(MESSAGES);
  
  class ClsDb extends ClsMessage {
    private $pdo = null;
    private $database = '';
    private $db_source = '';

    public function __construct($params){
      parent::__construct($params['debug']);
      $this->database = $params['database'];
      $this->db_source = $params['db_source'];
      $this->dbConnect();
    }

    private function dbConnect(){
      if(!$this->debug) {
        $db['host'] =  $this->db_source[$this->database]['prod']['host'];
        $db['user'] =  $this->db_source[$this->database]['prod']['user'];
        $db['pass'] =  $this->db_source[$this->database]['prod']['pass'];
      }
      else{
        $db['host'] =  $this->db_source[$this->database]['test']['host'];
        $db['user'] =  $this->db_source[$this->database]['test']['user'];
        $db['pass'] =  $this->db_source[$this->database]['test']['pass'];
      }

      try {
        $this->pdo = new PDO('mysql:host='.$db['host'].';dbname='.$this->database, $db['user'], $db['pass']);
      }
      catch (PDOException $e) {
        $this->SetMessage('Error', $e->getMessage(), __FUNCTION__, __LINE__);
      }
    }
    
    protected function dbInput($sql,$params) {
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);
      $affected = $stmt->rowCount();
      return $affected;
    }

    protected function dbOutput($sql,$params=[]) {
      $stmt = $this->pdo->prepare($sql);
      if( count($params)>0 ) {
        $stmt->execute($params);
      }
      else{
        $stmt->execute();
      }
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt->closeCursor();
      return $data;
    }

  }
?>