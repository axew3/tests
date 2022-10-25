<?php
// Read: Why notifications are stored like this?
// https://www.phpbb.com/community/viewtopic.php?t=2628741
// to know more about this and how to optimize for your requirement and to improve the query performance
// Post on topic if you know how could be improved

$w3all_url_to_cms = 'https://mysite.com/phpBB/';

$notifi_lang_types_ary = array(
      1  => "<b>New topic</b> by ",
      2  => "<b>Topic approval</b> request by ",
      3  => "<b>Quoted by</b> ",
      4  => "<b>Bookmarked Topic.</b> Reply from ",
      5  => "<b>Reply</b> from ",
      6  => "This is the phrase for TYPE 6",
      7  => "<b>Group request</b> from ",
      8  => "<b>Post approval request</b> by ",
      9  => "<b>Post reported:</b> ",
      10 => "<b>Topic approval</b> request by ",
      11 => "<b>Private Message</b> from ",
      12 => "<b>Activation required</b> for deactivated or newly registered user: ",
      13 => "This is the phrase for TYPE 13",
      14 => "This is the phrase for TYPE 14",
      15 => "<b>Group request approved</b> to join the group ",
      16 => "This is the phrase for TYPE 16",
      17 => "<b>Private Message report closed:</b>",
      18 => "<b>Report closed for post:</b> ",
      19 => "This is the phrase for TYPE 19",
      20 => "This is the phrase for TYPE 20",
      21 => "This is the phrase for TYPE 21",
      22 => "This is the phrase for TYPE 22",
      23 => "This is the phrase for TYPE 23",
      "on" => "on ",
      "forum" => "Forum: ",
    );

// using Ezsql libray for db queries, so just change the query to fit your needs, or pure sql
// note that you may want to avoid the JOIN into privmsgs. Refer to the linked phpBB topic for suggestions and how to

$w3all_phpbb_connection->get_results("SELECT N.*,P.post_id,P.topic_id,P.forum_id,P.poster_id,P.post_time,P.post_subject,U.username,PM.msg_id,PM.author_id
        FROM ".$w3all_config["table_prefix"]."notifications AS N
         JOIN ".$w3all_config["table_prefix"]."users AS U
         JOIN ".$w3all_config["table_prefix"]."posts AS P
         JOIN ".$w3all_config["table_prefix"]."privmsgs AS PM
        ON N.user_id = ". $phpbb_uid ."
         AND N.notification_type_id IN(3,4,5,6,8,9,20,22)
         AND N.notification_read = 0
         AND P.post_id = N.item_id
         AND U.user_id = P.poster_id
        OR N.user_id = ". $phpbb_uid ."
         AND N.notification_type_id IN(1,2,10,19)
         AND N.notification_read = 0
         AND P.topic_id = N.item_id
         AND U.user_id = P.poster_id
        OR N.user_id = ". $phpbb_uid ."
         AND N.notification_type_id IN(7,12)
         AND N.notification_read = 0
         AND U.user_id = N.item_id
         AND P.post_id = (SELECT MIN(post_id) FROM ".$config["table_prefix"]."posts AS post_id)
        OR N.user_id = ". $phpbb_uid ."
         AND N.notification_type_id IN(13,14,15,16,17,18,21)
         AND N.notification_read = 0
         AND U.user_id = 1
         AND P.post_id = (SELECT MIN(post_id) FROM ".$config["table_prefix"]."posts AS post_id)
        OR N.user_id = ". $phpbb_u ."
         AND N.notification_type_id = 11
         AND N.notification_read = 0
         AND P.post_id = (SELECT MIN(post_id) FROM ".$config["table_prefix"]."posts AS post_id)
          AND N.item_id = PM.msg_id
          AND U.user_id = PM.author_id
        GROUP BY N.notification_id ORDER BY N.notification_id DESC");

// not all but most important have been added
// iterate and display notifications like phpBB display notifications

  if(!empty($w3all_phpbb_unotifications))
  {

    echo '<ul class="">';

    foreach( $w3all_phpbb_unotifications as $nnn )
    {

      echo '<li class="">';

     foreach( $nnn as $nn => $n )
     {
       // Table: phpbb_notification_types

       if( $nn == 'notification_type_id' && $n == 1 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?t='.$nnn->topic_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[1] . $nnn->username . ': <a href="'.$n_url.'">' . $nnn->post_subject . '</a>' . '<br />' . $notifi_lang_types_ary["forum"] . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 2 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?t='.$nnn->topic_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[10] . $nnn->username . ': <a href="'.$n_url.'">' . $nnn->post_subject . '</a>' . '<br />Forum: ' . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 3 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[3] . $nnn->username . ' in:<br /><a href="'.$n_url.'">' . $nd["topic_title"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 4 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[4] . $nnn->username . ': <a href="'.$n_url.'">' . $nnn->post_subject . '</a>' . '<br />' . $notifi_lang_types_ary["forum"] . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 5 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[5] . $nnn->username . ' in topic:<br /><a href="'.$n_url.'">' . $nd["topic_title"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 6 )
        {
         //echo 'notification_type_id 6 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 7 )
        {
             $nd = unserialize($nnn->notification_data);
             $n_url = $w3all_url_to_cms . 'ucp.php?i=ucp_groups&mode=manage&action=list&g='.$nnn->item_parent_id;
             echo $notifi_lang_types_ary[7] . $nnn->username . ' to join the group: <a href="'.$n_url.'">' . $nd["group_name"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 8 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             $pu = empty($nd["post_username"]) ? $nnn->username : $nd["post_username"]; // or will display 'Anonimous' if from a guest
             echo $notifi_lang_types_ary[8] . $pu . ':<br /><a href="'.$n_url.'">' . $nnn->post_subject . '</a><br />'. $notifi_lang_types_ary['forum'] . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 9 )
        {
             $n_url = $w3all_url_to_cms . 'ucp.php?i=mcp_reports';
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[9] . ' <a href="'.$n_url.'">' . $nd["post_subject"] . '</a><br />Reason: ' . $nd["report_text"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);


        } elseif ( $nn == 'notification_type_id' && $n == 10 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?t='.$nnn->topic_id;
             $nd = unserialize($nnn->notification_data);
             $pu = empty($nd["post_username"]) ? $nnn->username : $nd["post_username"]; // or will display 'Anonimous' if from a guest
             echo $notifi_lang_types_ary[10] . $pu . ': <a href="'.$n_url.'">' . $nnn->post_subject . '</a><br />' . $notifi_lang_types_ary["forum"] . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 11 )
        {
             $n_url = $w3all_url_to_cms . 'ucp.php?i=pm&mode=view&f=0&p='.$nnn->item_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[11] . $nnn->username . ': <a href="'.$n_url.'">' . $nd["message_subject"] . '</a>' . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 12 )
        {
             $n_url = $w3all_url_to_cms . 'memberlist.php?mode=viewprofile&u='.$nnn->item_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[12] . ' <a href="'.$n_url.'">' . $nnn->username . '</a>' . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 13 )
        {
         //echo 'notification_type_id 13 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 14 )
        {
         //echo 'notification_type_id 14 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 15 )
        {
             $nd = unserialize($nnn->notification_data);
             $n_url = $w3all_url_to_cms . 'memberlist.php?mode=group&g='.$nnn->item_id;
             echo $notifi_lang_types_ary[15] . '<a href="'.$n_url.'">' . $nd["group_name"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 16 )
        {
             $n_url = $w3all_url_to_cms . 'mcp.php?r='.$nnn->item_parent_id.'&i=pm_reports&mode=pm_report_details';
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[16] . '<a href="'.$n_url.'">' . $nd["message_subject"] . '</a><br />' . $notifi_lang_types_ary["reason"] . $nd["report_text"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 17 )
        {
             $n_url = $w3all_url_to_cms . 'ucp.php?i=ucp_notifications&mode=notification_list';
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[17] . ' <a href="'.$n_url.'">' . $nd["message_subject"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 18 ) // notification.type.report_post_closed
        {
             $n_url = $w3all_url_to_cms . 'mcp.php?i=mcp_reports';
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[18] . ' <a href="'.$n_url.'">' . $nd["post_subject"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 19 )
        {
         //echo 'notification_type_id 19 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 20 )
        {
         //echo 'notification_type_id 20 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 21 )
        {
         //echo 'notification_type_id 21 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 22 )
        {
         //echo 'notification_type_id 22 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 23 )
        {
         //echo 'notification_type_id == 23 found!';
        }

      } // END foreach

      echo '</li>';

     } // END foreach

    echo '</ul>';

   } 
