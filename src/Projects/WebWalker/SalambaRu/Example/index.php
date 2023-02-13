<?php

use App\GoLogin;

use App\Projects\WebWalker\SalambaRu\Example\GoLoginProfile;
use App\Projects\WebWalker\SalambaRu\Example\ProxyTask;
use App\Projects\WebWalker\SalambaRu\Pages\FrontPage;
use Dotenv\Dotenv;

require_once '../../../../../vendor/autoload.php';

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

// $profile_id = $GoLogin->createProfile();
$profile_id = '63e7166f351ae696260fc2ed';

echo 'profile id = ' . $profile_id . PHP_EOL;
$profile = $GoLogin->gl->getProfile($profile_id);
echo 'new profile name = ' . $profile->name . PHP_EOL;

$orbita = $GoLogin->setOrbitaBrowser($profile_id);
$debugger_address = $orbita->start();

$driver = $GoLogin->runOrbitaBrowser($debugger_address);

$goSerf = new FrontPage($driver);
$goSerf->openFromVk('https://vk.com/salamba_ru')
       ->readArticle();

$allTabs = $driver->getWindowHandles();
foreach (array_reverse($allTabs) as $tab) {
    sleep(rand(1, 5));
    $driver->switchTo()->window($tab);
    $driver->close();
}

$orbita->stop();

sleep(10);
// $GoLogin->gl->delete($profile_id);
