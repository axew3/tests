<?php
/**
 *
 * Display Only First Post. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, axew3, http://axew3.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace w3all\displayonlyfirstpost\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Display Only First Post Event listener.
 */
class main_listener implements EventSubscriberInterface
{
  public static function getSubscribedEvents()
  {
    return [
     'core.user_setup'  => 'load_language_on_setup',
     'core.viewtopic_modify_post_data' => 'viewtopic_modify_post_data',
    ];
  }

  protected $config;
  protected $language;

  /**
   * Constructor
   *
   * @param \phpbb\language\language  $language Language object
   */
  public function __construct(\phpbb\config\config $config, \phpbb\language\language $language, \phpbb\user $user)
  {
    $this->config = $config;
    $this->language = $language;
    //$this->guid = $this->uid = '';
    $this->user = $user;
  }

  /**
   * Load common language files during user setup
   *
   * @param \phpbb\event\data $event  Event object
   */
  public function load_language_on_setup($e)
  {
    // may was faster than \phpbb\user $user
    //$this->guid = $e['user_data']['group_id'];
    //$this->uid = $e['user_data']['user_id'];

    $lang_set_ext = $e['lang_set_ext'];
    $lang_set_ext[] = [
      'ext_name' => 'w3all/displayonlyfirstpost',
      'lang_set' => 'common',
    ];
    $e['lang_set_ext'] = $lang_set_ext;
  }

  public function viewtopic_modify_post_data($e)
  {

   // if one or both settings are empty, ignore
   if( !empty($this->config['w3all_displayonlyfirstpost_u_groups']) && !empty($this->config['w3all_displayonlyfirstpost_forums_ids']) )
   {
     $u_groups = explode(",", $this->config['w3all_displayonlyfirstpost_u_groups']);
     $forums_ids = explode(",", $this->config['w3all_displayonlyfirstpost_forums_ids']);

     // if the user belong to one of the groups and the specified forum result to be on list, or the forums ids settings contains the word 'all' (all forums)
     if ( in_array($this->user->data['group_id'], $u_groups) && in_array($e['forum_id'], $forums_ids) OR in_array($this->user->data['group_id'], $u_groups) && in_array('all', $forums_ids) )
     {
      $epl = $e['post_list'];
       asort($epl); // so that if in descendant or ascendant order, the result will be the same: get the lower post ID
      $e['post_list'] = $epl;
      $vx = array_shift($e['post_list']);
      $tempRW = $e['rowset'];

    // Replacement mode:
    // $this->config['w3all_displayonlyfirstpost_rep_mode'] == 1 (Hide the entire post content)
    // $this->config['w3all_displayonlyfirstpost_rep_mode'] == 0 (Replace the post text with custom content)

      foreach($tempRW as $r => &$v){ // set as hidden (or replace the post content) all posts except the first (the lower ID)
       if($r != $vx){
        if( $this->config['w3all_displayonlyfirstpost_rep_mode'] > 0 )
        {
         $v['hide_post'] = 1;
        } else {
          $v['post_text'] = $this->language->lang('DISPLAYONLYFIRSTPOST_EVENT_REPLACEMENT_TEXT');
         }
       }
      }

      $e['rowset'] = $tempRW;
      unset($v,$tempRW);

     }
   } // END if( !empty($this->config['w3all_displayonlyfirstpost_u_groups'])

  } // END viewtopic_modify_post_data
}
