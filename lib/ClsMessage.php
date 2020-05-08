<?php
  /**
   * Message handling file
   * 2020-05-07
   * Chris Jokinen
   * 
   * could add different outputs based on debug setting.
   */

   class ClsMessage {
    private $messages = [];
    public $debug = false;

    public function __construct($debug){
      $this->debug = $debug;
    }

    public function SetMessage($lvl, $msg, $fnct='', $line=''){
      array_push( $this->messages, ['lvl' => $lvl, 'msg' => $msg, 'fnct' => $fnct, 'line' => $line] );
      if( $this->isError() ) {
        $this->close();
      }
    }

    public function GetMessages(){
      return $this->messages;
    }

    private function close() {
      echo "<table>";
      foreach ($this->messages as $m) {
        echo "<tr><th>LVL: </th><td>".$m['lvl']."</td></tr>";
        echo "<tr><th>MSG: </th><td>".$m['msg']."</td></tr>";
        echo "<tr><th>FUNCTION: </th><td>".$m['fnct']."</td></tr>";
        echo "<tr><th>LINE: </th><td>".$m['line']."</td></tr>";
        echo "<tr><td colspan='2'>&nbsp;</td></tr>";
      }
      echo "</table>";
      exit;
    }

    private function isError() {
      $rtn = false;
      if($this->debug) {
        foreach($this->messages AS $m) {
          if(strtolower($m['lvl']) == 'error') {
            $rtn = true;
            break 1;
          }
        }
      }
      return $rtn;
    }
  }
?>