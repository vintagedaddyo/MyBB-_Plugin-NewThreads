<?php
/**
 * MyBB
 * New Thread
 * Create a thread in the forum that you want
 * Website: http://www.mybb-es.com
 *
 * Author(s): Edson Ordaz & Vintagedaddyo
 * 
 * File: new.php
 *
 * Version: 1.1
 *
 */

define("IN_MYBB", 1);

define("KILL_GLOBALS", 1);

require_once "./global.php";

$lang->load("newthread", false, true);

$lang->max_options = $lang->sprintf($lang->max_options, $mybb->settings['maxpolloptions']);

add_breadcrumb("New Thread", "new.php");

$plugins->run_hooks('new_thread_start');

global $db, $mybb, $lang, $user;

$prefixselect = build_prefix_select($forum['fid'], $mybb->get_input('threadprefix', MyBB::INPUT_INT));

$icon = $post['icon'];

$posticons = get_post_icons();

// If we have a currently logged in user then fetch the change user box.

if($mybb->user['uid'] != 0)
{
	$mybb->user['username'] = htmlspecialchars_uni($mybb->user['username']);
	eval("\$loginbox = \"".$templates->get("changeuserbox")."\";");
}

// Otherwise we have a guest, determine the "username" and get the login box.

else
{
	if(!isset($mybb->input['previewpost']) && $mybb->input['action'] != "do_newthread")
	{
		$username = '';
	}
	else
	{
		$username = htmlspecialchars_uni($mybb->get_input('username'));
	}
	eval("\$loginbox = \"".$templates->get("loginbox")."\";");
}

$codebuttons = build_mycode_inserter();

$modoptions = $mybb->input['modoptions'];

if($modoptions['closethread'] == 1) {

	$closecheck = "checked=\"checked\"";

}

else {

	$closecheck = '';

}

if($modoptions['stickthread'] == 1) {

	$stickycheck = "checked=\"checked\"";

}

else {

	$stickycheck = '';

}

unset($modoptions);

$bgcolor = alt_trow();

if($mybb->usergroup['canmodcp']) {

eval("\$modoptions = \"".$templates->get("new_thread_modoptions")."\";");

}

if($mybb->user['uid']) {

eval("\$postoptions = \"".$templates->get("new_thread_postoptions")."\";");	

eval("\$subscriptionmethod = \"".$templates->get("new_thread_subscriptionmethod")."\";");

eval("\$pollbox = \"".$templates->get("new_thread_postpoll")."\";");

eval("\$savedraftbutton = \"".$templates->get("post_savedraftbutton", 1, 0)."\";");

require_once MYBB_ROOT."/inc/cachehandlers/interface.php";

$forumsselected = cache_forums_select();

eval("\$page_new_thread = \"".$templates->get("new_thread", 1, 0)."\";");

output_page($page_new_thread);

}

if($mybb->user['uid'] == 0) {

    error_no_permission();

}

?>