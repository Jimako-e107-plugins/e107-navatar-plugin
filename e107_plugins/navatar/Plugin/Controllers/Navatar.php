<?php

namespace Navatar\Plugin\Controllers;

use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use Navatar\Plugin\Models\User;

class Navatar extends Base
{

	/**
	 * Assigns navatars per user - Public static alias for
	 * Navatar\Plugin\Controllers\Navatar::assignNavatar()
	 *
	 * @param $data
	 *
	 * @return array|bool
	 */
	public static function assign($data)
	{
		$navatar = static::instantiate();

		return $navatar->assignNavatar($data);
	}


	/**
	 * Assigns navatars per user
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	public function assignNavatar($data)
	{
		$this->initializeData($data);

		if (User::fit($this->userId) /*&& ! file_exists($path)*/) {

			//clear image cache
			//\e107::getCache()->clearAll('image', '.*(\.cache\.bin)');
			\e107::getCache()->clearAll('browser');

			/** generate navatar & update user db record **/
			if ($this->generateNavatar()) {
				// database update
				return User::update($this->userId, $this->fileName);
			}

		}

		return false;
	}


	/**
	 * Initializes Navatar\Plugin\Controllers\Navatar object
	 *
	 * @param $data
	 */
	protected function initializeData($data)
	{
		$this->setUserId((int) $data['user_id'])
			->setUserName(vartrue($data['user_name'], ''))
			->setFileName($this->generateFileName($this->userId))
			->setInitialText($this->resolveInitialsSource())
			->setCharLength((int) $this->pref('character_length'))
			->setFontSize((float) $this->pref('font_size'))
			->setFontColor($this->pref('font_color'))
			->setFontVariant($this->pref('font_variant'))
			->setBackgroundColor($this->determineBgColor())
			->setDriver($this->pref('php_graphics_lib'))
			->setImageSize((int) $this->pref('navatar_size'))
			->setSavePath($this->conjureSavePath())
			->setImageQuality((int) $this->pref('navatar_quality'));
	}


	/**
	 * Sets current image quality - based on admin prefs
	 *
	 * @param $imageQuality
	 *
	 * @return $this
	 */
	private function setImageQuality($imageQuality)
	{
		$this->imageQuality = $imageQuality;

		return $this;
	}


	/**
	 * Sets current save path
	 *
	 * @param $savePath
	 *
	 * @return $this
	 */
	private function setSavePath($savePath)
	{
		$this->savePath = $savePath;

		return $this;
	}


	/**
	 * Sets current image size - - based on admin prefs
	 *
	 * @param $imageSize
	 *
	 * @return $this
	 */
	private function setImageSize($imageSize)
	{
		$this->imageSize = $imageSize;

		return $this;
	}


	/**
	 * Sets current php image driver - based on admin prefs
	 *
	 * @param $driver
	 *
	 * @return $this
	 */
	private function setDriver($driver)
	{
		$this->driver = $driver;

		return $this;
	}


	/**
	 * Sets current background color - based on admin prefs
	 *
	 * @param $bgColor
	 *
	 * @return $this
	 */
	private function setBackgroundColor($bgColor)
	{
		$this->bgColor = $bgColor;

		return $this;
	}


	/**
	 * Sets current font variant - based on admin prefs
	 *
	 * @param $fontVariant
	 *
	 * @return $this
	 */
	private function setFontVariant($fontVariant)
	{
		$this->fontVariant = $fontVariant;

		return $this;
	}


	/**
	 * Sets current font color - based on admin prefs
	 *
	 * @param $fontColor
	 *
	 * @return $this
	 */
	private function setFontColor($fontColor)
	{
		$this->fontColor = $fontColor;

		return $this;
	}


	/**
	 * Sets current font size - based on admin prefs
	 *
	 * @param $fontSize
	 *
	 * @return $this
	 */
	private function setFontSize($fontSize)
	{
		$this->fontSize = $fontSize;

		return $this;
	}


	/**
	 * Sets current character length - based on admin prefs
	 *
	 * @param $charLength
	 *
	 * @return $this
	 */
	protected function setCharLength($charLength)
	{
		$this->charLength = $charLength;

		return $this;
	}


	/**
	 * Sets current user's initial text
	 *
	 * @param $initialText
	 *
	 * @return $this
	 */
	private function setInitialText($initialText)
	{
		$this->initialText = $initialText;

		return $this;
	}


	/**
	 * Sets current user's navatar filename
	 *
	 * @param $fileName
	 *
	 * @return $this
	 */
	private function setFileName($fileName)
	{
		$this->fileName = $fileName;

		return $this;
	}


	/**
	 * Sets current user_name
	 *
	 * @param $userName
	 *
	 * @return $this
	 */
	protected function setUserName($userName)
	{
		$this->userName = $userName;

		return $this;
	}


	/**
	 * Sets current user_id
	 *
	 * @param $userId
	 *
	 * @return $this
	 */
	protected function setUserId($userId)
	{
		$this->userId = $userId;

		return $this;
	}


	/**
	 * Generates Navatar filename
	 *
	 * @param $userId
	 *
	 * @return string
	 */
	public function generateFileName($userId)
	{
		$fileName = 'ap_' . \e107::getParser()
				->leadingZeros($userId, 7) . '_navatar.png';

		return $fileName;
	}


	/**
	 * Determines current initials' text source - based on admin prefs
	 *
	 * @param $data
	 *
	 * @return int
	 */
	private function resolveInitialsSource()
	{
		if ($this->pref('initials_source') === 'realname') {
			return $this->findRealName($this->userId);
		}

		return $this->userName;
	}


	/**
	 * Gets user's Real Name (user_login) from database user table; if fails to
	 * return user_login returns user_name
	 *
	 * @param $userId
	 *
	 * @return int
	 */
	public function findRealName($userId)
	{
		$name = User::real($userId);

		return $name ?: $this->userName;
	}


	/**
	 * Resolves background color based on admin preference
	 *
	 * @return mixed|string
	 */
	private function determineBgColor()
	{
		if ($this->pref('random_bg_color')) {
			return Color::random();
		}

		return Color::exact();
	}


	/**
	 * Returns navatar save path.
	 *
	 * @return string
	 */
	public function conjureSavePath()
	{
		return e_AVATAR_UPLOAD . $this->fileName;
	}


	/**
	 * Generates Navatar image on failute catches the exception and
	 *  writes to system log
	 *
	 * @return bool
	 */
	public function generateNavatar()
	{

		/** generate navatar **/
		try {

			$saveDir = dirname($this->savePath);
			if ( ! is_dir($saveDir) && ! mkdir($saveDir, 0755, true)) {
				throw new \RuntimeException('Avatar upload directory "'
					. $saveDir . '" does not exist and could not be created.');
			}

			$initialText = trim((string) $this->initialText);
			if ($initialText === '') {
				throw new \RuntimeException('No initials source text found for user #'
					. $this->userId . '.');
			}

			$driver = ($this->driver === 'imagick') ? 'imagick' : 'gd';

			$avatar = new InitialAvatar();

			$avatar->name($initialText)->length($this->charLength)
				->font($this->fontVariant)->fontSize($this->fontSize)
				->size($this->imageSize)->background($this->bgColor)
				->color($this->fontColor)->{$driver}()->generate()
				->save($this->savePath, $this->imageQuality);

		}
		catch (\Throwable $e) { // \Throwable: PHP engine errors were fatal and unlogged with \Exception only

			$exceptionInfo = [
				'message' => $e->getMessage(),
				'file'    => $e->getFile(),
				'line'    => $e->getLine(),
			];

			$userInfo = [
				'user_id' => $this->userId,
			];

			/** write exception to system log **/
			\e107::getLog()
				->add('Navatar Generation failed!', $exceptionInfo, E_LOG_FATAL,
					'NAVATAR_01', LOG_TO_ADMIN, $userInfo);

			return false;
		}

		return true;
	}


}
