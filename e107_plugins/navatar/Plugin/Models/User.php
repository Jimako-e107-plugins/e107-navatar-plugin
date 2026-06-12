<?php

namespace Navatar\Plugin\Models;

class User
{


	public static function update($userId, $fileName)
	{
		$userId = (int) $userId;
		$sql = \e107::getDb();
		return $sql->update('user', ['user_image' => "-upload-{$fileName}", 'WHERE' => "(user_image = '' OR user_image IS NULL) AND user_id = {$userId}"]);
	}


	public static function fit($userId)
	{
		$userId = (int) $userId;
		$sql = \e107::getDb();
		return $sql->retrieve('user', 'user_id', "user_id = {$userId} AND (user_image = '' OR user_image IS NULL)");
	}


	/**
	 * Returns id/name pairs of non-banned users who have no avatar yet.
	 *
	 * @param int $limit
	 *
	 * @return array
	 */
	public static function withoutAvatar($limit = 100)
	{
		$limit = (int) $limit;
		$sql = \e107::getDb();
		$users = [];

		if ($sql->select('user', 'user_id, user_name',
			"user_ban = 0 AND (user_image = '' OR user_image IS NULL) ORDER BY user_id ASC LIMIT {$limit}")) {
			while ($row = $sql->fetch()) {
				$users[] = $row;
			}
		}

		return $users;
	}


	public static function rollback()
	{
		$sql = \e107::getDb();
		return $sql->update('user', ['user_image' => '', 'WHERE' => "user_image LIKE '%_navatar.png' "]);
	}


	public static function count()
	{
		$sql = \e107::getDb();
		return $sql->count('user', '(*)', "WHERE user_image LIKE '%_navatar.png' ");
	}



	public static function real($userId)
	{
		$userId = (int) $userId;
		$sql = \e107::getDb();
		return $sql->retrieve('user','user_login',"user_id = {$userId} ");
	}
}
