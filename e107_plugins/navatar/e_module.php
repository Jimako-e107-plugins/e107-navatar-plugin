<?php

if ( ! defined('e107_INIT')) {
	exit;
}

if ( ! file_exists(__DIR__ . '/vendor/autoload.php')) {

	define('NAVATAR_NO_DEPENDENCIES', true);

} else {

	require_once __DIR__ . '/vendor/autoload.php';

	$prefs = e107::getPlugPref('navatar');

	if ( ! empty($prefs['master_active'])) {

		$trigger = isset($prefs['user_trigger_event']) ? $prefs['user_trigger_event'] : 'both';

		if ($trigger === 'both' || $trigger === 'activate') {
			e107::getEvent()->register('user_signup_activated',
				['Navatar\Plugin\Listeners\UserEvents', 'activate']);
		}

		if ($trigger === 'both' || $trigger === 'login') {
			e107::getEvent()->register('login',
				['Navatar\Plugin\Listeners\UserEvents', 'login']);
		}
	}

	unset($prefs, $trigger);
}
