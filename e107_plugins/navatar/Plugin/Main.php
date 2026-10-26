<?php

namespace Navatar\Plugin;

abstract class Main
{
	/**
	 * @var instances[] reference to *Singleton* instances of any child
	 *     class.
	 */
	protected static $instances = [];
	/**
	 * Navatar preferences
	 *
	 * @var array
	 */
	protected $prefs;


	/**
	 * Main constructor.
	 *
	 */
	public function __construct()
	{
		$this->init(\e107::getPlugPref('navatar'));
	}


	/**
	 * Default preference values, used when a pref is missing from the
	 * database (e.g. plugin prefs were never imported).
	 *
	 * @var array
	 */
	protected static $defaults = [
		'master_active'      => 1,
		'user_trigger_event' => 'both',
		'navatar_size'       => 250,
		'navatar_quality'    => 100,
		'initials_source'    => 'realname',
		'php_graphics_lib'   => 'gd',
		'character_length'   => 2,
		'font_color'         => '#ffffff',
		'font_size'          => 0.5,
		'font_variant'       => '/fonts/NotoSans-Bold.ttf',
		'background_colors'  => ['#3e4fb4'],
		'random_bg_color'    => 1,
	];


	/**
	 * Initializes Navatar\Plugin\Main class
	 *
	 * @param $prefs
	 */
	protected function init($prefs)
	{
		if ( ! is_array($prefs)) {
			$prefs = [];
		}

		$colorsArray = [];
		if (isset($prefs['background_colors'])) {
			$colorsArray =
				$this->delimitedStringToArray($prefs['background_colors']);
		}
		if (empty($colorsArray)) {
			$colorsArray = self::$defaults['background_colors'];
		}
		$prefs['background_colors'] = $colorsArray;

		$this->setPrefs($prefs);
	}


	/**
	 * Returns a single preference value, falling back to the plugin default
	 * when the pref is missing or empty.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	protected function pref($key)
	{
		if (isset($this->prefs[$key]) && $this->prefs[$key] !== '') {
			return $this->prefs[$key];
		}

		return isset(self::$defaults[$key]) ? self::$defaults[$key] : null;
	}


	/**
	 * Returns the *Singleton* instance of the called class.
	 *
	 * @return static The *Singleton* instance.
	 */
	protected static function instantiate()
	{
		if ( ! isset(self::$instances[static::class])) {

			self::$instances[static::class] = new static();

		}

		return self::$instances[static::class];
	}


	/**
	 * Converts non-printing character delimited string to numeric array
	 *
	 * @param $inputString
	 *
	 * @return array[]|false|string[]
	 */
	protected function delimitedStringToArray($inputString)
	{
		if (is_array($inputString)) {
			return $inputString;
		}

		return preg_split('/\s+/', trim((string) $inputString), -1,
			PREG_SPLIT_NO_EMPTY);
	}


	/**
	 * Gets preference
	 *
	 * @return array
	 */
	public function getPrefs()
	{
		return $this->prefs;
	}


	/**
	 * Sets preference
	 *
	 * @param array $prefs
	 *
	 * @return \Navatar\Plugin\Main
	 */
	protected function setPrefs($prefs)
	{
		$this->prefs = $prefs;

		return $this;
	}
}