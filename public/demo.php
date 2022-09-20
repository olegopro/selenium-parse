<?php

use Facebook\WebDriver\Chrome\ChromeDevToolsDriver;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Interactions\Internal\WebDriverClickAction;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverPoint;

require '../vendor/autoload.php';

$chromeOptions = new ChromeOptions();
$chromeOptions->addArguments(['window-size=800,600']);

$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);

putenv('WEBDRIVER_CHROME_DRIVER=/Users/evilgazz/Downloads/chromedriver105');
$driver = ChromeDriver::start();

$devTools = new ChromeDevToolsDriver($driver);

$devTools->execute(
	'Network.setUserAgentOverride',
	['userAgent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:83.0) Gecko/20100101 Firefox/83.0']
);

// $driver->get("https://bot.sannysoft.com/");
// $driver->get("https://browserleaks.com/webgl");
// $driver->get("https://browserleaks.com/javascript");
// $driver->get("https://pixelscan.net/");

$driver->get("http://192.168.100.25/");

sleep(3);
$element = $driver->findElement(WebDriverBy::xpath("//*[@id='myform']"));
$input = $element->getAttribute('value');

if (!$input) {
	echo 'нету текста';
}
var_dump($input);

sleep(30);

