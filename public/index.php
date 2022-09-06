<?php


use App\Driver\Driver;
use App\Projects\MailRu\Tasks\RegisterAccount;


require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$driver = new Driver();
$createAccount = new RegisterAccount($driver->start());


$createAccount
	->openMainPage('https://mail.ru')
	->humanSleep(1, 3)
	->goToRegisterPage()
	->waitAfterLoad(5)
	->fillUsername('Martin')
	->humanSleep()
	->fillLastname('Reece')
	->selectDayBirthday(12)
	->selectMonthBirthday('Февраль')
	->selectYearBirthday('1982')
	->selectGender('male')
	->fillEmailName('tinuqasece')
	->fillPassword('Pa$$w0rd!1')
	->fillPasswordConfirm('Pa$$w0rd!1')
	->fillTelephone()
	->clickCreate();


