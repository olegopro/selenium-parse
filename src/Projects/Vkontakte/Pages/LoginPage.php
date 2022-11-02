<?php

namespace App\Projects\Vkontakte\Pages;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class LoginPage extends Page
{
	public function openLoginPage($url)
	{
		$this->driver->get($url);

		return $this;
	}

	public function login($login, $password)
	{

		if ($this->driver->getCurrentURL() === 'https://vk.com/feed') {
			return new Feed($this->driver);
		}

		echo __FUNCTION__ . ' Вводим имя' . PHP_EOL;
		$loginField = $this->driver->wait(5, 1000)->until(
			WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//input[@id='index_email']"))
		);

		$this->driver->action()->moveToElement($loginField, rand(1, 15), rand(1, 15));
		$loginField->click();
		$this->humanInputText($loginField, $login);

		$signInButton = $this->driver->wait(5, 1000)->until(
			WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//button[contains(@class, 'VkIdForm__signInButton')]"))
		);

		$this->driver->action()->moveToElement($signInButton, rand(1, 15), rand(1, 15));
		$signInButton->click();

		$passwordField = $this->driver->wait(5, 1000)->until(
			WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//input[@type='password']"))
		);

		echo __FUNCTION__ . ' Вводим пароль' . PHP_EOL;
		$this->driver->action()->moveToElement($passwordField, rand(1, 15), rand(1, 15));
		$passwordField->click();
		$this->humanInputText($passwordField, $password);

		echo __FUNCTION__ . ' Входим' . PHP_EOL;
		$submitButton = $this->driver->wait(5, 1000)->until(
			WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//button[@type='submit']"))
		);

		$this->driver->action()->moveToElement($submitButton, rand(1, 15), rand(1, 15));
		$submitButton->click();

		return new Feed($this->driver);

	}

}
