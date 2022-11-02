<?php

namespace App\Projects\Vkontakte\Pages;

use App\Helpers\humanSimulations;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriver;

abstract class Page
{
	use humanSimulations;

	protected $driver;

	public function __construct(RemoteWebDriver $driver)
	{
		$this->driver = $driver;
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
