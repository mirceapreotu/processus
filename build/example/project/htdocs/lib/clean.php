<?php

  error_reporting(E_ERROR);
  $dbh = mysql_connect('localhost', 'root', 'root');
  mysql_query('use apps_idaaa');


  // ######################################################
  // remove pf djs without events


  $sql = '
  SELECT
    DISTINCT d.id
  FROM
    djs AS d
    LEFT JOIN dj_event_relations AS der ON der.dj_id = d.id
  WHERE
    d.data_source="pf"
    AND der.event_id IS NULL
  ';
  
  $r = mysql_query($sql);

  echo '<h1>Remove Partyflock DJs without any event ('.mysql_affected_rows().')</h1>';

  while($obj = mysql_fetch_object($r))
  {
    echo "remove DJ $obj->id from...<br>";

    // remove genre relations
    echo "-> genres<br>";
    mysql_query('DELETE FROM dj_genre_relations WHERE dj_id='.$obj->id);

    // remove label relations
    echo "-> labels<br>";
    mysql_query('DELETE FROM dj_label_relations WHERE dj_id='.$obj->id);

    // remove events relations
    echo "-> events<br>";
    mysql_query('DELETE FROM dj_event_relations WHERE dj_id='.$obj->id);

    // remove member relations
    echo "-> members<br>";
    mysql_query('DELETE FROM member_dj_relations WHERE dj_id='.$obj->id);
    mysql_query('DELETE FROM member_dj_ratings WHERE dj_id='.$obj->id);

    // remove events relations
    echo "-> dj<br>";
    mysql_query('DELETE FROM djs WHERE id='.$obj->id);
    
    echo '<hr>';
  }


  // ######################################################
  // remove djs without genres


  $sql = '
  SELECT
    DISTINCT d.id
  FROM
    djs AS d
    LEFT JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
  WHERE
    dgr.genre_id IS NULL
  ';
  
  $r = mysql_query($sql);

  echo '<h1>Remove DJs without genres ('.mysql_affected_rows().')</h1>';

  while($obj = mysql_fetch_object($r))
  {
    echo "remove DJ $obj->id from...<br>";

    // remove label relations
    echo "-> labels<br>";
    mysql_query('DELETE FROM dj_label_relations WHERE dj_id='.$obj->id);

    // remove events relations
    echo "-> events<br>";
    mysql_query('DELETE FROM dj_event_relations WHERE dj_id='.$obj->id);

    // remove member relations
    echo "-> members<br>";
    mysql_query('DELETE FROM member_dj_relations WHERE dj_id='.$obj->id);
    mysql_query('DELETE FROM member_dj_ratings WHERE dj_id='.$obj->id);

    // remove events relations
    echo "-> dj<br>";
    mysql_query('DELETE FROM djs WHERE id='.$obj->id);
    
    echo '<hr>';
  }


  // ######################################################


  mysql_close($dbh);
  
  function debug($data)
  {
    echo '<div style="background:#ffc;padding:10px"><pre>';
    print_r($data);
    echo '</pre></div>';
    exit;
  }
  
?>