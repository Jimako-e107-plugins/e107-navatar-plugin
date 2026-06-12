<?php
namespace Navatar\Plugin\Jobs;

use Navatar\Plugin\Controllers\Navatar;
use Navatar\Plugin\Models\User;

class ProcessQueue
{
	/**
	 * Generates navatars for users who have no avatar yet.
	 *
	 * @param int $limit
	 *  Maximum number of users to process in one run.
	 *
	 * @return int
	 *  Number of navatars generated.
	 */
	public static function assign($limit = 100)
	{
		$generated = 0;

		foreach (User::withoutAvatar($limit) as $user) {

			$done = Navatar::assign([
				'user_id'   => $user['user_id'],
				'user_name' => $user['user_name'],
			]);

			if ($done) {
				$generated++;
			}
		}

		return $generated;
	}

}
