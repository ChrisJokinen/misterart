<?php
  /* 
    This can be automated by looking at the domain of the request
    ie:
      www.site.com
        $debug=false;
      test.site.com
        $debug=true;
  */
  $debug = true;
  require('./_config.php');
  require('lib/ClsPage.php');

  $params = [
    'debug'=>$debug,
    'database'=>'Employee',
    'db_source'=> $db_source
  ];

  $page = new ClsPage($params);

  $pageName = 'Departments';

  $pg = $page->loadHeader($pageName);

  $pg.= $page->loadNaviagtion();

  $pg.= "<div>".$pageName."</div>";

  $pg.= $page->loadFooter($pageName);

  echo $pg;
?>