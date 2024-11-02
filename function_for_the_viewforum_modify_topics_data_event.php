    public static function viewforum_modify_topics_data($e)
  {
    global $db;

    if( empty($e['rowset']) OR empty($e['topic_list']) ) return;

    $in = '0,'; // avoid any problem
    foreach( $e['topic_list'] as $t ){
      $in .= $t . ',';
    } $in = substr($in,0,-1);

     $sql = 'SELECT count(topic_id) as prCount, topic_id as tID
     FROM ' .POSTS_TABLE.'_replies
     WHERE topic_id IN('.$in.')
     GROUP BY topic_id';
     $r = $db->sql_query($sql);
     $res = $db->sql_fetchrowset($r);
     $db->sql_freeresult($r);

    if( empty($res) ) return;

    $rrr = array();
    foreach($e['rowset'] as $r => $rr){
      $rrr[$r] = $rr;
       foreach($res as $c){
        if($c['tID'] == $rrr[$r]['topic_id'])
        {
          $rrr[$r]['topic_posts_approved'] = $rrr[$r]['topic_posts_approved'] - $c['prCount'];
          }
        }
       }

    $e['rowset'] = $rrr;
  }
