<?php
/**
 * Plugin Name: LogPress
 * Plugin URI: #
 * Description: Displays latest page access
 * Author: Bimal Poudel
 * Version: 1.0.0
 * Author URI: http://bimal.org.np/
 */

if(!defined("ABSPATH")) die();

class logpress
{
	private $name = "debug_logpress";
	
	public function install()
	{
		$sql="
CREATE TABLE `debug_logpress` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `log_ip` varchar(255) NOT NULL DEFAULT '',
  `log_page` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
		dbDelta($sql);
	}
	
	public function uninstall()
	{
		$sql="DROP TABLE IF EXISTS `{$this->name}`;";
		dbDelta($sql);
	}
	
	public function display_logs()
	{
		require_once "logpress-report.php";
	}

	public function admin_menu()
	{
		$icon = "dashicons-format-aside";
		add_menu_page("LogPress", "LogPress Report", "manage_options", "logpress/logpress.php", array($this, "display_logs"), $icon, 81 );
	}
	
	public function log_access()
	{
		if(is_admin())
			return false;
		
		$path = addslashes($_SERVER["REQUEST_URI"]);
		$ip = addslashes($_SERVER["REMOTE_ADDR"]);
		$sql="INSERT INTO `{$this->name}` (`log_on`, `log_ip`, `log_page`) VALUES (NOW(), '{$ip}', '{$path}');";
		dbDelta($sql);
	}
}

require_once ABSPATH."wp-admin/includes/upgrade.php";

$logpress = new logpress();
register_activation_hook(__FILE__, array($logpress, "install"));
register_deactivation_hook(__FILE__, array($logpress, "uninstall"));
add_action("admin_menu", array($logpress, "admin_menu"));
add_action("init", array($logpress, "log_access"), 0);
