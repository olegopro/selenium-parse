<?php

namespace App\Projects\Vkontakte\Pages;

use Exception;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Throwable;

class Feed extends Page
{
	public function likePosts()
	{
		echo __FUNCTION__ . ' Ищем посты в ленте' . PHP_EOL;

		$this->driver->wait()->until(function () {
			return $this->driver->executeScript('return document.readyState') === 'complete';
		});

		$likeCount = 0;
		while ($likeCount < 100) {

			try {

				/** @var WebDriverElement $element */
				$element = $this->driver->wait(3, 300)->until(
					WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::xpath("//div[@aria-label='Отправить реакцию «Нравится»']"))
				);

			} catch (Exception $e) {

				sleep(5);
				echo __FUNCTION__ . 'Exception: ' . $e->getMessage() . PHP_EOL;
				$this->driver->executeScript('window.scrollTo(0,document.body.scrollHeight)');

			}

			echo __FUNCTION__ . ' Ставим лайк' . PHP_EOL;
			sleep(rand(2, 5));
			$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15))->click()->perform();
			echo __FUNCTION__ . ' Поставили: ' . ++$likeCount . ' лайков' . PHP_EOL;

		}

		echo __FUNCTION__ . ' Задание выполнено' . PHP_EOL;
	}

	public function checkCaptcha() {}
}

