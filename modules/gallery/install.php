<?php

/**
 * gallery install
 */

function gallery_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Gallery\', \'gallery\', \'Redaxmedia\', \'Create a lightbox enhanced image gallery\', \'1.2\', 1, 0)';
	mysql_query($query);
}

/**
 * gallery uninstall
 */

function gallery_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'gallery\' LIMIT 1';
	mysql_query($query);
}
?>