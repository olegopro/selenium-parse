<?php

use Facebook\WebDriver\Chrome\ChromeDevToolsDriver;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverExpectedCondition;
use SapiStudio\SeleniumStealth\SeleniumStealth;


require_once(__DIR__ . '/vendor/autoload.php');

$serverUrl = 'http://10.37.129.2:4444';

$chromeOptions = new ChromeOptions();
$chromeOptions->addArguments(['window-size=1920,1080']);
//$chromeOptions->addArguments(['--headless']);

$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);

$driver = RemoteWebDriver::create($serverUrl, $capabilities);
$devTools = new ChromeDevToolsDriver($driver);
$driver = (new SeleniumStealth($driver))->usePhpWebriverClient()->makeStealth();


$devTools->execute(
	'Network.setUserAgentOverride',
	['userAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36']
);

$devTools->execute(
	'Emulation.setUserAgentOverride',
	['userAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36']
);

//$driver->get("https://bot.sannysoft.com/");
//$driver->get("https://browserleaks.com/webgl");
//$driver->get("https://browserleaks.com/javascript");
//$driver->get("https://pixelscan.net/");

//$driver->quit();
