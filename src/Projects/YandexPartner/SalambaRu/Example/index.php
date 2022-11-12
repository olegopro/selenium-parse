<?php

use App\GoLogin;

use App\Projects\MailRu\Tasks\RegisterAccount;
use App\Projects\YandexPartner\SalambaRu\Example\GoLoginProfile;
use App\Projects\YandexPartner\SalambaRu\Example\ProxyTask;
use App\Projects\YandexPartner\SalambaRu\Pages\FrontPage;
use Dotenv\Dotenv;

require_once '/Volumes/SSD256/www/selenium-parse/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$GoLogin = new GoLoginProfile;

// $proxyData = (new ProxyTask)->setProxy([
// 	'task_proxy_ip'       => 'ip',
// 	'task_proxy_port'     => 'port',
// 	'task_proxy_type'     => 'http',
// 	'task_proxy_username' => 'username',
// 	'task_proxy_password' => 'password'
// ]);

$profile_id = $GoLogin->createProfile();

echo 'profile id = ' . $profile_id . PHP_EOL;
$profile = $GoLogin->gl->getProfile($profile_id);
echo 'new profile name = ' . $profile->name . PHP_EOL;

$orbita = $GoLogin->setOrbitaBrowser($profile_id);
$debugger_address = $orbita->start();

$driver = $GoLogin->runOrbitaBrowser($debugger_address);

$goSerf = new FrontPage($driver);

$goSerf->openPage('https://salamba.ru')
	   ->searchAndClickRandomArticle()
	   ->scrollDown(0)
	   ->findRandomArticlesInFooter(3);

sleep(rand(5, 15));

$driver->close();
$orbita->stop();

sleep(10);
$GoLogin->gl->delete($profile_id);
