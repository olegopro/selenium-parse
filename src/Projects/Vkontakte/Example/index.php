<?php

use App\Projects\Vkontakte\Pages\LoginPage;
use Facebook\WebDriver\Chrome\ChromeDevToolsDriver;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;

require '../../../../vendor/autoload.php';

$chromeOptions = new ChromeOptions();
$chromeOptions->addArguments(['--window-size=1024,768']);
$chromeOptions->addArguments(['--user-data-dir=/Users/evilgazz/Desktop/seleniumchrome']);

$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);

putenv('WEBDRIVER_CHROME_DRIVER=/Users/evilgazz/Downloads/chromedriver_mac107');
$driver = ChromeDriver::start($capabilities);

$devTools = new ChromeDevToolsDriver($driver);

/*$devTools->execute(
	'Network.setUserAgentOverride',
	['userAgent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:83.0) Gecko/20100101 Firefox/83.0']
);*/

$task = new LoginPage($driver);

$task->openLoginPage('https://vk.com')
	 ->login('login', 'password')
	 ->likePosts();
