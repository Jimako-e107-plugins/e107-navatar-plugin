<?php

if ( ! defined('e107_INIT')) {
	exit;
}

class navatar_cron
{
	public function config()
	{
		$cron = array();

		$cron[] = array(
			'name'            => "Create Navatars via cron",  // Displayed in admin area. .
			'function'        => "sheduledAssignNavatar",    // Name of the function which is defined below.
			'category'        => 'content',           // Choose between: mail, user, content, notify, or backup
			'description'     => "Creates navatars for already activated users who doesn't have an avatar"  // Displayed in admin area.
		);

		return $cron;
	}

	public function sheduledAssignNavatar()
	{
		if ( ! file_exists(__DIR__ . '/vendor/autoload.php')) {
			return;
		}

		require_once __DIR__ . '/vendor/autoload.php';

		$prefs = e107::getPlugPref('navatar');

		if (empty($prefs['master_active'])) {
			return;
		}

		$generated = Navatar\Plugin\Jobs\ProcessQueue::assign();

		if ($generated > 0) {
			e107::getLog()->add('Navatar cron',
				'Generated ' . $generated . ' navatar(s).', E_LOG_INFORMATIVE,
				'NAVATAR_02');
		}
	}

}
