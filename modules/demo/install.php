<?php

/* demo install */

function demo_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Demo\', \'demo\', \'Redaxmedia\', \'Enable anonymous login\', \'1.2\', 1, 0)';
	mysql_query($query);
}

/* demo uninstall */

function demo_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'demo\' LIMIT 1';
	mysql_query($query);
}
?>