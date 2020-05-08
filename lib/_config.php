<?php
  /**
   * Config file
   * 2020-05-07
   * Chris Jokinen
   * 
   * Use for state init of library classes
   */
  define('QUERIES',   'ClsQueries.php');
  define('DBCONN',    'ClsDb.php');
  define('MESSAGES',  'ClsMessage.php');
  
  $db_source = [
    'Employee' => [
      'test' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => ''
      ],
      'prod' => [
        'host' => 'misterart-test-vm',
        'user' => 'root',
        'pass' => '80b2fc03e32ed2d1d0b32bf9423c879e50fd325f85236207'
      ]
    ]
  ];
?>