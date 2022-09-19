<?php

namespace App\Projects\MailRu\Pages;

use App\SmsAPI\MainActivator;
use App\SmsAPI\Services\smsAcktiwator;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverWait;
use GuzzleHttp\Client;

class RegisterPage extends Page
{
	private $numberResponseId;

	public function fillUsername(string $text)
	{
		echo __FUNCTION__ . ' Вводим имя' . PHP_EOL;
		$element = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//input[@name='fname']")
		);

		$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));

		sleep(rand(0, 1));
		$element->click();

		sleep(rand(0, 1));
		$this->humanInputText($element, $text);

		return $this;
	}

	public function fillLastname(string $text)
	{
		echo __FUNCTION__ . ' Вводим фамилию' . PHP_EOL;
		$element = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//input[@name='lname']")
		);

		$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));

		sleep(rand(0, 1));
		$element->click();

		sleep(rand(0, 1));
		$this->humanInputText($element, $text);

		return $this;
	}

	public function selectDayBirthday(int $day)
	{
		echo __FUNCTION__ . ' Выбираем день рождения' . PHP_EOL;
		$elementList = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//form[@data-test-id='signup-first-step']//span[text()='День']")
		);

		sleep(rand(0, 1));
		$elementList->click();

		$elementItem = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//div[@data-test-id='birth-date__day__menu']//span[text()='$day']")
		);
		$this->driver->action()->moveToElement($elementItem)->perform();

		sleep(rand(0, 1));
		$elementItem->click();

		return $this;
	}

	public function selectMonthBirthday(string $month)
	{
		echo __FUNCTION__ . ' Выбираем месяц рождения' . PHP_EOL;
		$elementList = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//form[@data-test-id='signup-first-step']//span[text()='Месяц']")
		);

		sleep(rand(0, 1));
		$elementList->click();

		$elementItem = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//div[@data-test-id='birth-date__month__menu']//span[text()='$month']")
		);
		$this->driver->action()->moveToElement($elementItem)->perform();

		sleep(rand(0, 1));
		$elementItem->click();

		return $this;
	}

	public function selectYearBirthday(int $year)
	{
		echo __FUNCTION__ . ' Выбираем год рождения' . PHP_EOL;
		$elementList = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//form[@data-test-id='signup-first-step']//span[text()='Год']")
		);

		sleep(rand(0, 1));
		$elementList->click();

		$elementItem = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//div[@data-test-id='birth-date__year__menu']//span[text()='$year']")
		);
		$this->driver->action()->moveToElement($elementItem)->perform();

		sleep(rand(0, 1));
		$elementItem->click();

		return $this;
	}

	public function selectGender(string $gender)
	{
		echo __FUNCTION__ . ' Выбираем пол' . PHP_EOL;
		switch ($gender) {
			case 'male':
				$element = $this->waitForVisible(
					$this->driver,
					WebDriverBy::xpath("//label[@data-test-id='gender-male']")
				);

				sleep(rand(0, 1));
				$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));
				$element->click();
				break;

			case 'female':
				$element = $this->waitForVisible(
					$this->driver,
					WebDriverBy::xpath("//label[@data-test-id='gender-female']")
				);

				sleep(rand(0, 1));
				$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));
				$element->click();
				break;
		}

		return $this;
	}

	public function fillEmailName(string $name)
	{
		echo __FUNCTION__ . ' Вводим имя почты' . PHP_EOL;
		$element = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//input[@name='username']")
		);

		$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));
		$element->click();

		$this->humanInputText($element, $name);

		try {
			$element = $this->waitForVisible(
				$this->driver,
				WebDriverBy::xpath("//*[@data-test-id='exists']")
			);

			echo __FUNCTION__ . ' Аккаунт уже существует' . PHP_EOL;
			$name . rand(10, 99);

			echo ' Пробуем ввести новый логин' . PHP_EOL;
			$this->fillEmailName($name);

		} catch (Exception $exception) {
			echo __FUNCTION__ . ' Ввели логин' . PHP_EOL;
			// echo $exception->getMessage() . PHP_EOL;
		}

		return $this;
	}

	public function fillPassword(string $password)
	{
		echo __FUNCTION__ . ' Вводим пароль' . PHP_EOL;
		$element = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//input[@name='password']")
		);

		$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));
		$element->click();

		$this->humanInputText($element, $password);

		return $this;
	}

	public function fillPasswordConfirm(string $password)
	{
		echo __FUNCTION__ . ' Вводим подтверждение пароля' . PHP_EOL;
		$element = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//input[@name='repeatPassword']")
		);

		$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));
		$element->click();

		$this->humanInputText($element, $password);

		return $this;
	}

	public function fillRecoveryEmail(string $recoveryEmail)
	{
		echo __FUNCTION__ . ' Вводим резервную почту' . PHP_EOL;
		$switchToEmail = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//a[@data-test-id='phone-number-switch-link']")
		);

		$this->driver->action()->moveToElement($switchToEmail, rand(1, 15), rand(1, 15));

		sleep(rand(0, 3));
		$switchToEmail->click();

		$extraEmail = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//input[@data-test-id='extra-email']")
		);

		sleep(rand(0, 3));
		$extraEmail->click();

		sleep(rand(0, 3));
		$this->humanInputText($extraEmail, $recoveryEmail);

		return $this;
	}

	public function fillTelephone()
	{
		echo __FUNCTION__ . ' Вводим номер телефона' . PHP_EOL;
		$element = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//input[@data-test-id='phone-input']")
		);

		$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));
		$element->click();

		$client = new Client();
		$smsAktiwator = new MainActivator(new smsAcktiwator($_ENV['SMS_SERVICE_API_KEY']), $client);

		$getNumber = $smsAktiwator->getNumber('ma', 1);
		$responseToArray = explode(':', $getNumber);

		$numberResponse = [
			'status' => $responseToArray[0]
		];

		switch ($numberResponse['status']) {
			case 'ACCESS_NUMBER':

				$numberResponse = [
					'id'     => $responseToArray[1],
					'number' => $responseToArray[2]
				];

				$this->numberResponseId = $numberResponse['id'];

				echo 'Номер: ' . $numberResponse['number'] . PHP_EOL;
				break;

			case 'NO_NUMBERS':
				throw new Exception('Нет номеров');

			case 'NO_BALANCE':
				throw new Exception('Нет денег на балансе');
		}

		global $telephone;
		$telephone = $numberResponse['number'];

		//$element->sendKeys();
		$this->driver->getKeyboard()
					 ->sendKeys([WebDriverKeys::BACKSPACE]);

		$this->humanInputText($element, $numberResponse['number']);

		return $this;
	}

	public function clickCreate()
	{
		echo __FUNCTION__ . ' Нажимаем создать аккаунт' . PHP_EOL;
		$element = $this->waitForVisible(
			$this->driver,
			WebDriverBy::xpath("//form[@data-test-id='signup-first-step']/button[@data-test-id='first-step-submit']")
		);

		$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));
		$element->click();

		$this->solvingCaptcha();

		return $this;
	}

	private function solvingCaptcha()
	{
		$captcha_api_key = $_ENV['CAPTCHA_KEY'];

		try {
			$element = $this->waitForVisible(
				$this->driver,
				WebDriverBy::xpath("//h3[@data-test-id='verification-step-header-recaptcha']"),
				3
			);
			echo __FUNCTION__ . ' Нашли капчу' . PHP_EOL;

			sleep(1);
			$this->driver->executeScript(
				'
				;(function autorun() {
					return (findRecaptchaClients = () => {
						//		 eslint-disable-next-line camelcase
						if (typeof ___grecaptcha_cfg !== \'undefined\') {
							// eslint-disable-next-line camelcase, no-undef
							return Object.entries(___grecaptcha_cfg.clients).map(([cid, client]) => {
								const data = {
									id: cid,
									version: cid >= 10000 ? \'V3\' : \'V2\'
								}
								const objects = Object.entries(client).filter(([_, value]) => value && typeof value === \'object\')
				
								objects.forEach(([toplevelKey, toplevel]) => {
									const found = Object.entries(toplevel).find(([_, value]) => value && typeof value === \'object\' && \'sitekey\' in value && \'size\' in value)
				
									if (typeof toplevel === \'object\' && toplevel instanceof HTMLElement && toplevel[\'tagName\'] === \'DIV\') {
										data.pageurl = toplevel.baseURI
									}
				
									if (found) {
										const [sublevelKey, sublevel] = found
				
										data.sitekey = sublevel.sitekey
										const callbackKey = data.version === \'V2\' ? \'callback\' : \'promise-callback\'
										const callback = sublevel[callbackKey]
										if (!callback) {
											data.callback = null
											data.function = null
										} else {
											data.function = callback
											const keys = [cid, toplevelKey, sublevelKey, callbackKey].map(key => `[\'${key}\']`).join(\'\')
											data.callback = `___grecaptcha_cfg.clients${keys}`
										}
									}
								})
								return data
							})
						}
						return []
					})
				
					return autorun
				})()	
			'
			);

			echo __FUNCTION__ . ' Ищем callback' . PHP_EOL;

			sleep(5);
			echo __FUNCTION__ . ' Возвращаем последний callback' . PHP_EOL;
			$callback = $this->driver->executeScript('return findRecaptchaClients()');

			$lastCallback = array_pop($callback);

			$callbackId = $lastCallback['id'];
			$callbackName = $lastCallback['callback'] . '()';
			$pageurl = strtok($lastCallback['pageurl'], '?');
			$sitekey = $lastCallback['sitekey'];

			var_dump(__FUNCTION__ . $callbackName) . PHP_EOL;

			$client = new Client();
			$response = $client->request('GET', "https://rucaptcha.com/in.php?key=$captcha_api_key&method=userrecaptcha&googlekey=$sitekey&pageurl=$pageurl");

			sleep(10);
			var_dump($response->getBody()->__toString()) . PHP_EOL;

			$responseKey = explode('|', $response->getBody()->__toString());

			$responseCaptcha = $client->request('GET', "https://rucaptcha.com/res.php?key=$captcha_api_key&action=get&id=$responseKey[1]");

			while ($responseCaptcha->getBody()->__toString() === 'CAPCHA_NOT_READY') {
				var_dump($responseCaptcha->getBody()->__toString()) . PHP_EOL;
				$responseCaptcha = $client->request('GET', "https://rucaptcha.com/res.php?key=$captcha_api_key&action=get&id=$responseKey[1]");
				sleep(10);
			}

			$responseCaptchaKey = explode('|', $responseCaptcha->getBody()->__toString());
			var_dump($responseCaptchaKey) . PHP_EOL;

			sleep(rand(0, 1));

			if ($callbackId == 0) {
				$this->driver->executeScript("document.querySelector('#g-recaptcha-response').value = '$responseCaptchaKey[1]'");
			} else {
				$this->driver->executeScript("document.querySelector('#g-recaptcha-response-$callbackId').value = '$responseCaptchaKey[1]'");
			}

			sleep(3);
			$this->driver->executeScript($callbackName);
			$this->clickResume();

		} catch (NoSuchElementException $e) {
			echo __FUNCTION__ . ' Captcha не обнаружена идем дальше' . PHP_EOL;
			$this->fillReceivedSms();
		}
	}

	private function fillReceivedSms()
	{
		$this->checkIfNeedCallBypass();

		try {
			sleep(rand(5, 15));
			$element = $this->waitForVisible(
				$this->driver,
				WebDriverBy::xpath("//input[@data-test-id='code']"),
				2
			);

			$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));

			sleep(rand(0, 1));
			$element->click();

			sleep(rand(0, 1));
			$client = new Client();
			$smsAktiwator = new MainActivator(new smsAcktiwator($_ENV['SMS_SERVICE_API_KEY']), $client);

			$getStatus = $smsAktiwator->getStatus($this->numberResponseId);

			$timer = 0;
			while ($getStatus === 'STATUS_WAIT_CODE') {
				$getStatus = $smsAktiwator->getStatus($this->numberResponseId);

				echo __FUNCTION__ . ' Текущий статус смс: ' . $getStatus . PHP_EOL;
				sleep(30);

				$timer += 30;

				if ($timer === 90) {
					try {
						echo __FUNCTION__ . ' Пытаемся получить смс код повторно' . PHP_EOL;
						$element = $this->waitForVisible(
							$this->driver,
							WebDriverBy::xpath("//a[@data-test-id='resend-code-link']"),
							5
						);

						$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));

						sleep(rand(0, 3));
						$element->click();
					} catch (Exception $exception) {
						echo $exception->getMessage() . PHP_EOL;

						echo __FUNCTION__ . ' Проверяем наличие капчи' . PHP_EOL;
						$this->solvingCaptcha();
					}
				}
			}

			$getStatusResponse = explode(':', $getStatus);

			if ($getStatusResponse[0] === 'STATUS_OK') {
				echo __FUNCTION__ . ' СМС: ' . $getStatusResponse[1] . PHP_EOL;
			}

			echo __FUNCTION__ . ' Вводим СМС' . PHP_EOL;
			$this->humanInputText($element, $getStatusResponse[1]);
			$this->clickResume();

		} catch (NoSuchElementException $exception) {
			echo __FUNCTION__ . ' Форма для получения смс кода не найдена. Идем дальше' . PHP_EOL;
			try {
				echo __FUNCTION__ . 'Ищем кнопку "Продолжить"' . PHP_EOL;
				$this->clickResume();
			} catch (Exception $exception) {
				echo $exception->getMessage();
				echo __FUNCTION__ . 'Кнопка "Продолжить" не найдена' . PHP_EOL;
				$this->setMinimumConfig();
			}
		}

	}

	private function clickResume()
	{
		try {
			echo __FUNCTION__ . ' Ищем кнопку продолжить' . PHP_EOL;
			$element = $this->waitForVisible(
				$this->driver,
				WebDriverBy::xpath("//button[@data-test-id='verification-next-button']"),
				3
			);

			$this->driver->action()->moveToElement($element, rand(1, 15), rand(1, 15));
			$element->click();
		} catch (NoSuchElementException $exception) {
			echo __FUNCTION__ . ' Не нашли кнопку продолжить' . PHP_EOL;
		}
	}

	public function setMinimumConfig()
	{
		$currentUrl = strtok($this->driver->getCurrentURL(), '?');
		echo __FUNCTION__ . ' Текущая страница: ' . $currentUrl . PHP_EOL;

		try {
			if ($currentUrl === 'https://e.mail.ru/inbox') {
				$buttonStart = $this->waitForVisible(
					$this->driver,
					WebDriverBy::xpath("//button[@data-test-id='onboarding-button-start']")
				);

				$this->driver->action()->moveToElement($buttonStart, rand(1, 15), rand(1, 15));

				sleep(rand(2, 3));
				$buttonStart->click();

				$buttonEmailBoxType = $this->waitForVisible(
					$this->driver,
					WebDriverBy::xpath("//button[@data-test-id='onboarding-button-step']")
				);

				$this->driver->action()->moveToElement($buttonEmailBoxType, rand(1, 15), rand(1, 15));

				sleep(rand(1, 3));
				$buttonEmailBoxType->click();

				$buttonComplete = $this->waitForVisible(
					$this->driver,
					WebDriverBy::xpath("//button[@data-test-id='onboarding-button-complete']")
				);

				$this->driver->action()->moveToElement($buttonComplete, rand(1, 15), rand(1, 15));

				sleep(rand(1, 3));
				$buttonComplete->click();

				$buttonCancelReserveEmail = $this->waitForVisible(
					$this->driver,
					WebDriverBy::xpath("//button[@data-test-id='recovery-addEmail-cancel']")
				);

				$this->driver->action()->moveToElement($buttonCancelReserveEmail, rand(1, 15), rand(1, 15));

				sleep(rand(1, 3));
				$buttonCancelReserveEmail->click();

				echo 'Аккаунт успешно создан!' . PHP_EOL;
			}

		} catch (Exception $exception) {
			echo $exception->getMessage() . PHP_EOL;
			echo 'Пробуем найти капчу' . PHP_EOL;
			$this->solvingCaptcha();
		}
	}

	private function checkIfNeedCallBypass()
	{
		try {
			echo __FUNCTION__ . ' Ищем форму "Мы Вам звоним"' . PHP_EOL;
			$element = $this->waitForVisible(
				$this->driver,
				WebDriverBy::xpath("//h3[@data-test-id='verification-step-header-callui']"),
				2
			);

			echo __FUNCTION__ . ' Нашли форму "Мы Вам звоним" ждем 60 секунд';
			sleep(rand(60, 70));
			$elementGoNext = $this->waitForVisible(
				$this->driver,
				WebDriverBy::xpath("//a[@data-test-id='resend-callui-link']")
			);

			$this->driver->action()->moveToElement($elementGoNext, rand(1, 15), rand(1, 15));

			sleep(rand(0, 1));
			$elementGoNext->click();
		} catch (NoSuchElementException $e) {
			echo __FUNCTION__ . ' Форма "Мы Вам звоним не найдена" идём дальше' . PHP_EOL;
		}
	}
}
