<?php
/**
 * MyBB 1.8
 * New Thread
 * Create a thread in the forum that you want
 * Website: http://www.mybb-es.com
 *
 * Author(s): Edson Ordaz & Vintagedaddyo
 * 
 * File: inc/plugins/new.php
 *
 * Version: 1.1.2
 *
 */

if(!defined("IN_MYBB"))
{

	die("This file cannot be accessed directly.");

}

$plugins->add_hook("new_thread_start","new_thread_function");

function new_info()
{
	return array(
		"name"			=> "New Thread",
		"description"	=> "Create a thread in the forum that you want",
		"website"		=> "http://www.mybb-es.com",
		"author"		=> "Edson Ordaz & minor edits by Vintagedaddyo",
		"authorsite"	=> "mailto:nicedo_eeos@hotmail.com",
		"version"		=> "1.1.2",
		"compatibility" => "18*",
		"guid"			=> "d38c8b8f8919cfccdb9596354fe510b5"
	);
}


function new_activate(){

	global $db;

  	$new_thread  = array(
		"tid" => "0",
		"title" => "new_thread",
		"template" => $db->escape_string('<html>
   <head>
      <title>{$lang->post_new_thread}</title>
      {$headerinclude}
      {$post_javascript}
   </head>
   <body>
      {$header}
      {$preview}
      {$thread_errors}
      {$moderation_notice}
      <form action="newthread.php" method="post" enctype="multipart/form-data" name="input">
         <input type="hidden" name="my_post_key" value="{$mybb->post_code}" />
         <table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
            <tr>
               <td class="thead" colspan="2"><strong>{$lang->post_new_thread}</strong></td>
            </tr>
            {$loginbox}
            <tr>
               <td class="trow2" width="20%"><strong>{$lang->thread_subject}</strong></td>
               <td class="trow2">{$prefixselect} <input type="text" class="textbox" name="subject" size="40" maxlength="85" value="{$subject}" tabindex="1" /></td>
            </tr>
            {$posticons} 
            <tr>
               <td class="trow2" valign="top"><strong>{$lang->your_message}</strong><br /><span style="font-size:18px"><strong>{$lang->forumbit_forum}:</strong></span><br />{$forumsselected}</td>
               <td class="trow2" valign="top">
                  <textarea name="message" id="message" rows="20" cols="70" tabindex="2" style="height: 540px;">{$message}</textarea>
                  {$codebuttons}
               </td>
            </tr>
            {$postoptions}
            {$modoptions}
            {$subscriptionmethod}
            {$pollbox}
         </table>
         <br />
         <div style="text-align:center"><input type="submit" class="button" name="submit" value="{$lang->post_thread}" tabindex="4" accesskey="s" />  <input type="submit" class="button" name="previewpost" value="{$lang->preview_post}" tabindex="5" />{$savedraftbutton}</div>
         <input type="hidden" name="action" value="do_newthread" />
         <input type="hidden" name="posthash" value="{$posthash}" />
         <input type="hidden" name="attachmentaid" value="" />
         <input type="hidden" name="attachmentact" value="" />
         <input type="hidden" name="quoted_ids" value="{$quoted_ids}" />
         <input type="hidden" name="tid" value="{$tid}" />
         {$editdraftpid}
      </form>
      {$forumrules}
      {$footer}
   </body>
</html>'),
		"sid" => "-1",
	);

	$new_thread_postoptions  = array(
		"tid" => "0",
		"title" => "new_thread_postoptions",
		"template" => $db->escape_string('<tr>
   <td class="trow1" valign="top"><strong>{$lang->post_options}</strong></td>
   <td class="trow1"><span class="smalltext">
      <label><input type="checkbox" class="checkbox" name="postoptions[signature]" value="1" tabindex="7"{$postoptionschecked[\'signature\']} /> {$lang->options_sig}</label><br />
      <label><input type="checkbox" class="checkbox" name="postoptions[disablesmilies]" value="1" tabindex="9"{$postoptionschecked[\'disablesmilies\']} /> {$lang->options_disablesmilies}</label></span>
   </td>
</tr>'),
		"sid" => "-1",
	);


	$new_thread_modoptions  = array(
		"tid" => "0",
		"title" => "new_thread_modoptions",
		"template" => $db->escape_string('<tr>
   <td class="trow1" valign="top"><strong>{$lang->mod_options}</strong></td>
   <td class="trow1"><span class="smalltext">
      <label><input type="checkbox" class="checkbox" name="modoptions[closethread]" value="1"{$closecheck} />&nbsp;{$lang->close_thread}</label><br />
      <label><input type="checkbox" class="checkbox" name="modoptions[stickthread]" value="1"{$stickycheck} />&nbsp;{$lang->stick_thread}</label></span>
   </td>
</tr>'),
		"sid" => "-1",
	);

	$new_thread_postpoll  = array(
		"tid" => "0",
		"title" => "new_thread_postpoll",
		"template" => $db->escape_string('<tr>
   <td class="trow1" valign="top"><strong>{$lang->poll}</strong><br /><span class="smalltext">{$lang->poll_desc}</span></td>
   <td class="trow1">
      <span class="smalltext"><label><input type="checkbox" class="checkbox" name="postpoll" value="1" {$postpollchecked} /><strong>{$lang->poll_check}</strong></label><br />
      {$lang->num_options} <input type="text" class="textbox" name="numpolloptions" value="{$numpolloptions}" size="10" /> {$lang->max_options}</span>
   </td>
</tr>'),
		"sid" => "-1",
	);

	$new_thread_subscriptionmethod  = array(
		"tid" => "0",
		"title" => "new_thread_subscriptionmethod",
		"template" => $db->escape_string('<tr>
   <td class="trow1" valign="top"><strong>{$lang->thread_subscription_method}</strong><br /><span class="smalltext">{$lang->thread_subscription_method_desc}</span></td>
   <td class="trow1"><span class="smalltext">
      <label><input type="radio" name="postoptions[subscriptionmethod]" {$subscribe}value="" style="vertical-align: middle;" /> {$lang->no_subscribe}</label><br />
      <label><input type="radio" name="postoptions[subscriptionmethod]" {$nonesubscribe}value="none" style="vertical-align: middle;" /> {$lang->no_subscribe_notification}</label><br />
      <label><input type="radio" name="postoptions[subscriptionmethod]" {$emailsubscribe}value="email" style="vertical-align: middle;" /> {$lang->instant_email_subscribe}</label><br />
      <label><input type="radio" name="postoptions[subscriptionmethod]" {$pmsubscribe}value="pm" style="vertical-align: middle;" /> {$lang->instant_pm_subscribe}</label></span>
   </td>
</tr>'),
		"sid" => "-1",
	);

	$db->insert_query("templates", $new_thread);

	$db->insert_query("templates", $new_thread_postoptions);	

	$db->insert_query("templates", $new_thread_modoptions);

	$db->insert_query("templates", $new_thread_postpoll);

	$db->insert_query("templates", $new_thread_subscriptionmethod);
}

function new_deactivate(){

	global $db;

	$db->delete_query("templates","title = 'new_thread'");

	$db->delete_query("templates","title = 'new_thread_postoptions'");	

	$db->delete_query("templates","title = 'new_thread_modoptions'");

	$db->delete_query("templates","title = 'new_thread_postpoll'");

	$db->delete_query("templates","title = 'new_thread_subscriptionmethod'");
}

function new_thread_function()
{
	function cache_forums_select($size=24,$width=230)
	{
		global $forum_cache, $cache;
		
		if(!$forum_cache)
		{
			$forum_cache = $cache->read("forums");

			if(!$forum_cache)
			{
				$cache->update_forums();

				$forum_cache = $cache->read("forums", 1);

			}
		}

		$forum = array();

	    foreach($forum_cache as $forums)
	    {
			
			if($forums['type'] != "c")
			{

				$forum .= "<option value=\"".$forums['fid']."\">".$forums['name']."</option>"; 

			}
	    }
	    $forum_selected = "<select name=\"fid\" size=\"{$size}\" style=\"width: {$width}px\">
		".$forum."</select>";

		return $forum_selected;

	}
}

?>