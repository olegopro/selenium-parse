<?php

namespace App\Projects\MailRu\Pages;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverTargetLocator;

class MainPage extends Page
{
	public function openMainPage($url)
	{
		$this->driver->get($url);

		return $this;
	}

	public function goToRegisterPage()
	{
		$element = $this->driver->findElement(WebDriverBy::xpath(".//a[contains(@href, 'account.mail.ru/signup')]"));
		$element->click();

		return new RegisterPage($this->driver);
	}
}
