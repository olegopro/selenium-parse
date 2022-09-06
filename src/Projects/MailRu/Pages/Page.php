<?php

namespace App\Projects\MailRu\Pages;

use App\Helpers\humanSimulations;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverExpectedCondition;

abstract class Page
{
	use humanSimulations;

	protected $driver;

	public function __construct(RemoteWebDriver $driver)
	{
		$this->driver = $driver;
	}

	/**
	 * @param WebDriver $driver
	 * @param WebDriverBy $element
	 *
	 * @return WebDriverElement
	 */
	static function waitForVisible(WebDriver $driver, WebDriverBy $element, $timeOut = 15)
	{
		return $driver->wait($timeOut, 500)->until(
			WebDriverExpectedCondition::visibilityOfElementLocated($element)
		);
	}

	private function waitUntilDomReadyState(WebDriver $webDriver, $sleep_time)
	{
		$webDriver->wait()->until(function () {
			return $this->driver->executeScript('return document.readyState') === 'complete';
		});

		return sleep($sleep_time);
	}

	public function waitAfterLoad($sleep_time)
	{
		$this->waitUntilDomReadyState($this->driver, $sleep_time);

		return $this;
	}
}
