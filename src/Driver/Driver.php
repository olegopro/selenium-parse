<?php

namespace App\Driver;

use Facebook\WebDriver\Chrome\ChromeDevToolsDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\WebDriverPlatform;
use ZipArchive;

class Driver
{
	protected $driver;
	private $capabilities;

	public function __construct()
	{

		$chromeOptions = new ChromeOptions();

		$chromeOptions->addArguments(['window-size=1920,1080']);
		$chromeOptions->addArguments(['user-agent=Mozilla/5.0 (iPad; CPU OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/79.0.3945.73 Mobile/15E148 Safari/604.1']);

		$this->capabilities = DesiredCapabilities::chrome();
		$this->capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);

	}

	public function start($serverUrl = 'http://10.37.129.2:4444')
	{
		$this->driver = RemoteWebDriver::create($serverUrl, $this->capabilities);
		$devTools = new ChromeDevToolsDriver($this->driver);


		$devTools->execute(
			'Network.setUserAgentOverride',
			['userAgent' => 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.82 Safari/537.36']
		);

		return $this->driver;
	}
}
