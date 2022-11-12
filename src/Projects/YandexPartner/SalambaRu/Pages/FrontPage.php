<?php

namespace App\Projects\YandexPartner\SalambaRu\Pages;

use Exception;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverExpectedCondition;

class FrontPage extends Page
{

	private $element;

	public function openPage($url)
	{
		$this->driver->get($url);

		return $this;
	}

	public function searchAndClickRandomArticle()
	{

		$this->driver->wait()->until(function () {
			return $this->driver->executeScript('return document.readyState') === 'complete';
		});

		echo __FUNCTION__ . ' Ищем статьи на главной' . PHP_EOL;

		try {

			$elements = $this->driver->findElements(WebDriverBy::xpath("//section[contains(@class,'category-section')]//img"));

			$rand = array_rand($elements);
			$randomElement = $elements[$rand];

			echo $randomElement->getText() . PHP_EOL;

			$toggle = 0;
			while ($toggle < 10) {

				try {

					sleep(2);

					$this->driver->action()->moveToElement($randomElement)->perform();
					$randomElement->click();

					$toggle = 10;

				} catch (Exception $exception) {
					echo $exception->getMessage() . PHP_EOL;
					$this->oneScrollDown();
					$toggle++;
				}
			}

		} catch (Exception $e) {

			echo __FUNCTION__ . 'Элементов не найдено' . PHP_EOL;
			echo __FUNCTION__ . 'Exception: ' . $e->getMessage() . PHP_EOL;

		}

		return $this;

	}

	public function scrollDown($startHeight)
	{
		$bodyScrollHeight = $this->driver->executeScript("return document.body.scrollHeight");

		$innerHeight = $this->driver->executeScript("return window.innerHeight + window.scrollY") + 500;
		$offsetHeight = $this->driver->executeScript("return document.body.offsetHeight");

		while ($startHeight < $offsetHeight) {

			$this->clickToADS();

			$randScrollLength = rand(100, 600);
			$startHeight += $randScrollLength;

			$scrollReverse = rand(1, 100);

			if ($scrollReverse > 90) {
				$minus = '-';
				$startHeight -= $randScrollLength * 1.6;

			} else {
				$minus = '';
			}

			// echo $scrollReverse . PHP_EOL;

			$this->driver->executeScript("window.scrollBy(0," . $minus . $randScrollLength . ")");

			// echo '--startHeight--';
			// var_dump($startHeight) . PHP_EOL;
			// echo '--bodyScrollHeight--';
			// var_dump($bodyScrollHeight) . PHP_EOL;

			usleep(mt_rand(200000, 5000000));

		}

		return $this;

	}

	public function findRandomArticlesInFooter($count = 1)
	{

		$innerCount = 0;

		while ($count > $innerCount) {

			$elements = $this->driver->findElements(WebDriverBy::xpath("//ul[contains(@class, 'random-posts')]//a"));

			$rand = array_rand($elements);
			$randomElement = $elements[$rand];

			echo $randomElement->getText() . PHP_EOL;

			try {

				usleep(mt_rand(500000, 3000000));
				$this->driver->action()->moveToElement($randomElement)->perform();

				usleep(mt_rand(100000, 800000));
				$randomElement->click();

				echo __FUNCTION__ . ' Проход по случайным ссылкам из подвала: ' . ++$innerCount . PHP_EOL;

			} catch (Exception $exception) {

				echo $exception->getMessage() . PHP_EOL;

				usleep(mt_rand(500000, 3000000));
				$this->oneScrollDown();

			}

			usleep(mt_rand(500000, 3000000));

			$this->scrollDown($this->driver->executeScript("return window.innerHeight + window.scrollY"));

		}

		return $this;

	}

	private function oneScrollDown()
	{

		$startHeight = 0;

		$randScrollLength = rand(200, 800);
		$startHeight += $randScrollLength;
		$this->driver->executeScript("window.scrollBy(0," . $randScrollLength . ")");

		usleep(mt_rand(200000, 3000000));
	}

	public function clickToADS()
	{

		try {
			$yandexAds = $this->driver->wait(2, 300)->until(
				WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//div[contains(@id,'yandex_rtb_R-A-1982030-5')]"))
			);

			$this->driver->action()->moveToElement($yandexAds, 20, 20)->perform();

			sleep(rand(1, 3));
			$yandexAds->click();

			$this->scrollDown(0);

		} catch (Exception $exception) {
			echo __FUNCTION__ . ' Рекламного блока не видно ' . PHP_EOL;
			echo $exception->getMessage() . PHP_EOL;
		}

		return;

	}
}
