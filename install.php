<?php
error_reporting(0);

/* include core files */

include_once('includes/check.php');
include_once('includes/clean.php');
include_once('includes/detection.php');
include_once('includes/filesystem.php');
include_once('includes/generate.php');
include_once('includes/get.php');
include_once('includes/loader.php');
include_once('includes/misc.php');
include_once('includes/modules.php');
include_once('includes/password.php');
include_once('includes/query.php');
include_once('includes/replace.php');
include_once('includes/startup.php');

/* install post */

install_post();

/* write database config */

write_config();
include_once('config.php');

/* startup redaxscript */

startup();

/* include language files */

include_once('languages/' . LANGUAGE . '.php');
include_once('languages/misc.php');

/* define meta */

define('TITLE', l('installation'));
define('ROBOTS', 'none');

/* call loader else render template */

if (FIRST_PARAMETER == 'loader' && (SECOND_PARAMETER == 'styles' || SECOND_PARAMETER == 'scripts'))
{
	echo loader(SECOND_PARAMETER, 'outline');
}
else
{
	include_once('templates/install/install.phtml');
}

/**
 * install
 */

function install()
{
	global $d_host, $d_name, $d_user, $d_password, $d_prefix, $d_salt, $name, $user, $password, $email;
	$r['create_database'] = 'CREATE DATABASE IF NOT EXISTS ' . $d_name;
	$r['grant_privileges'] = 'GRANT ALL PRIVILEGES ON ' . $d_name . '.* TO \'' . $d_user . '\'@\'' . $d_host . '\' IDENTIFIED BY \'' . $d_password . '\'';
	$r['flush_privileges'] = 'FLUSH PRIVILEGES';
	$r['create_articles'] = 'CREATE TABLE IF NOT EXISTS ' . $d_name . '.' . $d_prefix . 'articles (
		id int(10) NOT NULL AUTO_INCREMENT,
		title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		alias varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		author varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		description varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		keywords varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		text longtext COLLATE utf8_unicode_ci,
		language char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
		template varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		date datetime DEFAULT NULL,
		category int(10) DEFAULT NULL,
		headline int(1) DEFAULT NULL,
		infoline int(1) DEFAULT NULL,
		comments int(1) DEFAULT NULL,
		status int(1) DEFAULT NULL,
		rank int(10) DEFAULT NULL,
		access varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY(id)
	)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	COLLATE = utf8_unicode_ci
	AUTO_INCREMENT = 2';
	$r['create_categories'] = 'CREATE TABLE IF NOT EXISTS ' . $d_name . '.' . $d_prefix . 'categories (
		id int(10) NOT NULL AUTO_INCREMENT,
		title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		alias varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		author varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		description varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		keywords varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		language char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
		template varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		parent int(10) DEFAULT NULL,
		status int(1) DEFAULT NULL,
		rank int(10) DEFAULT NULL,
		access varchar(255)COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY(id)
	)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	COLLATE = utf8_unicode_ci
	AUTO_INCREMENT = 2';
	$r['create_comments'] = 'CREATE TABLE IF NOT EXISTS ' . $d_name . '.' . $d_prefix . 'comments (
		id int(10) NOT NULL AUTO_INCREMENT,
		author varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		email varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		url varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		text longtext COLLATE utf8_unicode_ci,
		language char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
		date datetime DEFAULT NULL,
		article int(10) DEFAULT NULL,
		status int(1) DEFAULT NULL,
		rank int(10) DEFAULT NULL,
		access varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY(id)
	)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	COLLATE = utf8_unicode_ci
	AUTO_INCREMENT = 1';
	$r['create_extras'] = 'CREATE TABLE IF NOT EXISTS ' . $d_name . '.' . $d_prefix . 'extras (
		id int(10) NOT NULL AUTO_INCREMENT,
		title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		alias varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		author varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		text longtext COLLATE utf8_unicode_ci,
		language char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
		date datetime DEFAULT NULL,
		category int(10) DEFAULT NULL,
		article int(10) DEFAULT NULL,
		headline int(1) DEFAULT NULL,
		status int(1) DEFAULT NULL,
		rank int(10) DEFAULT NULL,
		access varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY(id)
	)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	COLLATE = utf8_unicode_ci
	AUTO_INCREMENT = 6';
	$r['create_groups'] = 'CREATE TABLE IF NOT EXISTS ' . $d_name . '.' . $d_prefix . 'groups (
		id int(10) NOT NULL AUTO_INCREMENT,
		name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		alias varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		description varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		categories varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		articles varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		extras varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		comments varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		groups varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		users varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		modules varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		settings int(1) DEFAULT NULL,
		filter int(1) DEFAULT NULL,
		status int(1) DEFAULT NULL,
		PRIMARY KEY(id)
	)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	COLLATE = utf8_unicode_ci
	AUTO_INCREMENT = 3';
	$r['create_modules'] = 'CREATE TABLE IF NOT EXISTS ' . $d_name . '.' . $d_prefix . 'modules (
		id int(10) NOT NULL AUTO_INCREMENT,
		name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		alias varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		author varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		description varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		version varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		status int(1) DEFAULT NULL,
		access varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY(id)
	)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	COLLATE = utf8_unicode_ci
	AUTO_INCREMENT = 2';
	$r['create_settings'] = 'CREATE TABLE IF NOT EXISTS ' . $d_name . '.' . $d_prefix . 'settings (
		id int(10) NOT NULL AUTO_INCREMENT,
		name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		value varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY(id)
	)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	COLLATE = utf8_unicode_ci
	AUTO_INCREMENT = 26';
	$r['create_users'] = 'CREATE TABLE IF NOT EXISTS ' . $d_name . '.' . $d_prefix . 'users (
		id int(10) NOT NULL AUTO_INCREMENT,
		name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		user varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		password varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		email varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		description varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		language char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
		first datetime DEFAULT NULL,
		last datetime DEFAULT NULL,
		status int(1) DEFAULT NULL,
		groups varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY(id)
	)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	COLLATE = utf8_unicode_ci
	AUTO_INCREMENT = 2';
	$r['insert_articles'] = 'INSERT INTO ' . $d_name . '.' . $d_prefix . 'articles (id, title, alias, author, description, keywords, text, language, template, date, category, headline, infoline, comments, status, rank, access) VALUES (1, \'Welcome\', \'welcome\', \'' . $user . '\', \'\', \'\', \'<p>Congratulations! Redaxscript has been successfully installed.</p>\', \'\', \'\', \'' . NOW . '\', 1, 1, 0, 0, 1, 1, \'0\')';
	$r['insert_categories'] = 'INSERT INTO ' . $d_name . '.' . $d_prefix . 'categories (id, title, alias, author, description, keywords, language, template, parent, status, rank, access) VALUES (1, \'Home\', \'home\', \'' . $user . '\', \'\', \'\', \'\', \'\', 0, 1, 1, \'0\')';
	$r['insert_extras'] = 'INSERT INTO ' . $d_name . '.' . $d_prefix . 'extras (id, title, alias, author, text, language, date, category, article, headline, status, rank, access) VALUES (1, \'Categories\', \'categories\', \'' . $user . '\', \'<function>navigation_list|categories->array(children => 1)</function>\', \'\', \'' . NOW . '\', 0, 0, 1, 1, 1, \'0\'), (2, \'Articles\', \'articles\', \'' . $user . '\', \'<function>navigation_list|articles</function>\', \'\', \'' . NOW . '\', 0, 0, 1, 1, 2, \'0\'), (3, \'Comments\', \'comments\', \'' . $user . '\', \'<function>navigation_list|comments</function>\', \'\', \'' . NOW . '\', 0, 0, 1, 1, 3, \'0\'), (4, \'Languages\', \'languages\', \'' . $user . '\', \'<function>languages_list</function>\', \'\', \'' . NOW . '\', 0, 0, 1, 0, 4, \'0\'), (5, \'Templates\', \'templates\', \'' . $user . '\', \'<function>templates_list</function>\', \'\', \'' . NOW . '\', 0, 0, 1, 0, 5, \'0\'), (6, \'Footer\', \'footer\', \'' . $user . '\', \'<div class=\"box_first grid_space s1o5\"><h3 class=\"title_footer\">Lorem ipsum</h3><ul class=\"list_footer\"><li><a>Lorem ipsum</a></li><li><a>Lorem ipsum</a></li><li><a>Lorem ipsum</a></li></ul></div><div class=\"box_second grid_space s1o5\"><h3 class=\"title_footer\">Lorem ipsum</h3><ul class=\"list_footer\"><li><a>Lorem ipsum</a></li><li><a>Lorem ipsum</a></li><li><a>Lorem ipsum</a></li></ul></div><div class=\"box_third grid_space s1o5\"><h3 class=\"title_footer\">Lorem ipsum</h3><ul class=\"list_footer\"><li><a>Lorem ipsum</a></li><li><a>Lorem ipsum</a></li><li><a>Lorem ipsum</a></li></ul></div><div class=\"box_fourth grid_space s1o5\"><h3 class=\"title_footer\">Lorem ipsum</h3><ul class=\"list_footer\"><li><a>Lorem ipsum</a></li><li><a>Lorem ipsum</a></li><li><a>Lorem ipsum</a></li></ul></div><div class=\"box_last grid_space s1o5\"><h3 class=\"title_footer\"><function>l|administration</function></h3><function>login_list<function></div>\', \'\', \'' . NOW . '\', 0, 0, 0, 0, 6, \'0\')';
	$r['insert_groups'] = 'INSERT INTO ' . $d_name . '.' . $d_prefix . 'groups (id, name, alias, description, categories, articles, extras, comments, groups, users, modules, settings, filter, status) VALUES (1, \'Administrators\', \'administrators\', \'Unlimited access\', \'1, 2, 3\', \'1, 2, 3\', \'1, 2, 3\', \'1, 2, 3\', \'1, 2, 3\', \'1, 2, 3\', \'1, 2, 3\', 1, 0, 1), (2, \'Members\', \'members\', \'Default members group\', \'0\', \'0\', \'0\', \'0\', \'0\', \'0\', \'0\', 0, 1, 1)';
	$r['insert_settings'] = 'INSERT INTO ' . $d_name . '.' . $d_prefix . 'settings (id, name, value) VALUES (1, \'language\', \'detect\'), (2, \'template\', \'default\'), (3, \'title\', \'Redaxscript\'), (4, \'author\', \'\'), (5, \'copyright\', \'\'), (6, \'description\', \'Ultra lightweight website engine\'), (7, \'keywords\', \'\'), (8, \'robots\', \'all\'), (9, \'email\', \'' . $email . '\'), (10, \'subject\', \'Redaxscript\'), (11, \'notification\', \'0\'), (12, \'charset\', \'utf-8\'), (13, \'divider\', \'&#8201;&#8226;&#8201;\'), (14, \'time\', \'H:i\'), (15, \'date\', \'d.m.Y\'), (16, \'homepage\', \'0\'), (17, \'limit\', \'5\'), (18, \'order\', \'asc\'), (19, \'pagination\', \'1\'), (20, \'moderation\', \'0\'), (21, \'registration\', \'1\'), (22, \'verification\', \'0\'), (23, \'reminder\', \'1\'), (24, \'captcha\', \'0\'), (25, \'blocker\', \'1\')';
	if (file_exists('modules/call_home/install.php'))
	{
		$r['insert_modules'] = 'INSERT INTO ' . $d_name . '.' . $d_prefix . 'modules (name, alias, author, description, version, status, access) VALUES (\'Call home\', \'call_home\', \'Redaxmedia\', \'Call home module\', \'1.2\', 1, 0)';
	}
	$r['insert_users'] = 'INSERT INTO ' . $d_name . '.' . $d_prefix . 'users (id, name, user, password, email, description, language, first, last, status, groups) VALUES (1, \'' . $name . '\', \'' . $user . '\', \'' . sha1($password) . $d_salt . '\', \'' . $email . '\', \'God admin\', \'\', \'' . NOW . '\', \'' . NOW . '\', 1, \'1\')';

	/* install database */

	foreach ($r as $key => $value)
	{
		mysql_query($value);
	}

	/* email login information */

	$url_link = anchor_element('', '', '', ROOT, ROOT);
	$body_array = array(
		l('user') => $user,
		l('password') => $password,
		code1 => '<br />',
		l('url') => $url_link
	);
	send_mail($email, $name, s('email'), s('author'), l('installation'), $body_array);
}

/*
 * install form
 */

function install_form()
{
	global $d_host, $d_name, $d_user, $d_password, $d_prefix, $name, $user, $password, $email;

	/* collect output */

	$output = '<h2 class="title_content">' . l('installation') . '</h2>';
	$output .= form_element('form', 'form_install', 'js_check_required js_note_required js_accordion form_default accordion accordion_default', '', '', '', 'action="' . FILE . '" method="post" autocomplete="off"');

	/* collect database set */

	$output .= '<fieldset class="js_set_accordion js_set_active set_accordion set_accordion_default set_active">';
	$output .= '<legend class="js_title_accordion js_title_active title_accordion title_accordion_default title_active">' . l('database_setup') . '</legend>';
	$output .= '<ul class="js_box_accordion box_accordion box_accordion_default">';
	$output .= '<li>' . form_element('text', 'd_host', 'js_required field_text field_note', 'd_host', $d_host, '* ' . l('host'), 'maxlength="50" required="required" autofocus="autofocus"') . '</li>';
	$output .= '<li>' . form_element('text', 'd_name', 'js_required field_text field_note', 'd_name', $d_name, '* ' . l('name'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('text', 'd_user', 'js_required field_text field_note', 'd_user', $d_user, '* ' . l('user'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('password', 'd_password', 'js_unmask_password field_text', 'd_password', $d_password, l('password'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . form_element('text', 'd_prefix', 'field_text', 'd_prefix', $d_prefix, l('prefix'), 'maxlength="50"') . '</li>';
	$output .= '</ul></fieldset>';

	/* collect account set */

	$output .= '<fieldset class="js_set_accordion js_set_accordion_last set_accordion set_accordion_default set_accordion_last">';
	$output .= '<legend class="js_title_accordion title_accordion title_accordion_default">' . l('account_create') . '</legend>';
	$output .= '<ul class="js_box_accordion box_accordion box_accordion_default">';
	$output .= '<li>' . form_element('text', 'name', 'js_required field_text field_note', 'name', $name, '* ' . l('name'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('text', 'user', 'js_required field_text field_note', 'user', $user, '* ' . l('user'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('password', 'password', 'js_unmask_password js_required field_text field_note', 'password', $password, '* ' . l('password'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('email', 'email', 'js_required field_text field_note', 'email', $email, '* ' . l('email'), 'maxlength="50" required="required"') . '</li>';
	$output .= '</ul></fieldset>';

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'd_salt', hash_generator(40));
	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= form_element('button', '', 'field_button_large', 'install_post', l('install'));
	$output .= '</form>';
	echo $output;
}

/**
 * install post
 */

function install_post()
{
	global $d_host, $d_name, $d_user, $d_password, $d_prefix, $d_salt, $name, $user, $password, $email;

	/* clean post */

	$d_host = clean($_POST['d_host'], 5);
	$d_name = clean($_POST['d_name'], 5);
	$d_user = clean($_POST['d_user'], 5);
	$d_password = clean($_POST['d_password'], 5);
	$d_prefix = clean($_POST['d_prefix'], 5);
	$d_salt = clean($_POST['d_salt'], 5);
	$name = clean($_POST['name'], 0);
	$user = clean($_POST['user'], 0);
	$password = clean($_POST['password'], 0);
	$email = clean($_POST['email'], 3);

	/* validate post */

	if ($d_host == '')
	{
		$d_host = 'localhost';
	}
	if ($user == '')
	{
		$user = 'admin';
	}
	if ($password == '')
	{
		$password = hash_generator(10);
	}
}

/*
 * install status
 */

function install_notification()
{
	global $d_host, $d_name, $d_user, $d_password, $name, $user, $password, $email;
	if (is_writable('config.php') == '')
	{
		$error = l('file_permission_grant') . l('colon') . ' config.php';
	}
	else if (DB_CONNECTED == 0)
	{
		$error = l('database_failed');
		if (DB_ERROR)
		{
			$error .= l('colon') . ' ' . DB_ERROR;
		}
	}

	/* validate post */

	else if ($_POST['install_post'])
	{
		if ($name == '')
		{
			$error = l('name_empty');
		}
		else if ($user == '')
		{
			$error = l('user_empty');
		}
		else if ($password == '')
		{
			$error = l('password_empty');
		}
		else if ($email == '')
		{
			$error = l('email_empty');
		}
		else if (check_login($user) == 0)
		{
			$error = l('user_incorrect');
		}
		else if (check_login($password) == 0)
		{
			$error = l('password_incorrect');
		}
		else if (check_email($email) == 0)
		{
			$error = l('email_incorrect');
		}
	}

	/* handle error */

	if ($error)
	{
		$output = '<div class="box_note note_error">' . $error . l('point') . '</div>';
	}

	/* handle success */

	else if (check_install() == 1)
	{
		$output = '<div class="box_note note_success">' . l('installation_completed') . l('point') . '</div>';
	}
	echo $output;
}

/*
 * check install
 * 
 * @return integer
 */

function check_install()
{
	global $name, $user, $password, $email;
	if ($_POST['install_post'] && DB_CONNECTED == 1 && $name && check_login($user) == 1 && check_login($password) == 1 && check_email($email) == 1)
	{
		$output = 1;
	}
	else
	{
		$output = 0;
	}
	return $output;
}

/**
 * write config
 */

function write_config()
{
	if ($_POST['install_post'])
	{
		global $d_host, $d_name, $d_user, $d_password, $d_prefix, $d_salt;
		$file = fopen('config.php', 'w+');
		$contents =
'<?php

/* config database */

function d($name = \'\')
{
	$d[\'host\'] = \'' . $d_host . '\';
	$d[\'name\'] = \'' . $d_name . '\';
	$d[\'user\'] = \'' . $d_user . '\';
	$d[\'password\'] = \'' . $d_password . '\';
	$d[\'prefix\'] = \'' . $d_prefix . '\';
	$d[\'salt\'] = \'' . $d_salt . '\';
	$output = $d[$name];
	return $output;
}
?>';
		fwrite($file, $contents);
		fclose($file);
	}
}

/**
 * head
 */

function head()
{
	$output = '<base href="' . ROOT . '/" />' . PHP_EOL;
	$output .= '<title>' . TITLE . '</title>' . PHP_EOL;
	$output .= '<meta http-equiv="content-type" content="text/html; charset=' . s('charset') . '" />' . PHP_EOL;
	if (check_install() == 1)
	{
		$output .= '<meta http-equiv="refresh" content="2; url=' . ROOT . '" />' . PHP_EOL;
	}
	$output .= '<meta name="generator" content="' . l('redaxscript') . ' ' . l('redaxscript_version') . '" />' . PHP_EOL;
	$output .= '<meta name="robots" content="' . ROBOTS . '" />' . PHP_EOL;
	echo $output;
}

/**
 * center
 */

function center()
{
	/* check token */

	if ($_POST && $_POST['token'] != TOKEN)
	{
		$output = '<div class="box_note note_error">' . l('token_incorrect') . l('point') . '</div>';
		echo $output;
		return;
		break;
	}

	/* routing */

	install_notification();
	if (check_install() == 1)
	{
		install();
	}
	else
	{
		install_form();
	}
}
?>