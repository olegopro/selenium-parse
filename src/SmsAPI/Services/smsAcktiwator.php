<?php

namespace App\SmsAPI\Services;

use App\SmsAPI\ServiceInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

class smsAcktiwator implements ServiceInterface
{
	protected $endPoint = 'https://sms-acktiwator.ru/stubs/handler_api.php';
	protected $apiKey;

	/**
	 * @var ClientInterface
	 */
	protected $client;
	protected $logger;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	protected function request($action, $params = [])
	{
		$params['api_key'] = $this->apiKey;
		$params['action'] = $action;

		try {
			return $this->client->request('GET', $this->endPoint, ['query' => $params])->getBody();
		} catch (RequestException $exception) {
			echo $exception;
		}

		exit();
	}

	public function setClient(ClientInterface $client)
	{
		$this->client = $client;
	}

	public function setLogger($logger)
	{
		$this->logger = $logger;
	}

	public function getBalance()
	{
		$response = $this->request('getBalance');

		return $response->getContents();
	}

	public function getNumber($service, $country)
	{
		$response = $this->request('getNumber', [
			'service' => $service,
			'country' => $country
		]);

		return $response->getContents();
	}

	public function setReady($id)
	{
		$response = $this->request('setStatus', [
			'id'     => $id,
			'status' => 1
		]);

		return $response->getContents();
	}

	public function setUsed($id)
	{
		$response = $this->request('setStatus', [
			'id'     => $id,
			'status' => 8
		]);

		return $response->getContents();
	}

	public function setCancel($id)
	{
		$response = $this->request('setStatus', [
			'id'     => $id,
			'status' => -1
		]);

		return $response->getContents();
	}

	public function setComplete($id)
	{
		$response = $this->request('setStatus', [
			'id'     => $id,
			'status' => 6
		]);
	}

	public function getList()
	{
		return [
			'vk' => 'Вконтакте',
			'ok' => 'Одноклассники',
			'wa' => 'Whatsapp',
			'vi' => 'Viber',
			'tg' => 'Telegram',
			'wb' => 'WeChat',
			'go' => 'Google,youtube,Gmail',
			'av' => 'avito',
			'fb' => 'facebook',
			'tw' => 'Twitter',
			'ub' => 'Uber',
			'qw' => 'Qiwi',
			'gt' => 'Gett',
			'sn' => 'OLX.ua',
			'ig' => 'Instagram',
			'ss' => 'SeoSprint',
			'ym' => 'Юла',
			'ma' => 'Mail.ru',
			'mm' => 'Microsoft',
			'uk' => 'Airbnb',
			'me' => 'Line messenger',
			'mb' => 'Yahoo',
			'we' => 'Aol',
			'bd' => 'Rambler.ru',
			'kp' => 'Tencent QQ',
			'dt' => 'Такси Максим',
			'ya' => 'Яндекс',
			'mt' => 'Skout',
			'oi' => 'Momo',
			'fd' => 'GetResponse',
			'zz' => 'Zalo',
			'kt' => 'KakaoTalk',
			'ot' => 'Любой другой'
		];
	}

	public function getStatus($id)
	{
		$response = $this->request('getStatus', [
			'id' => $id
		]);

		return $response->getContents();
	}
}
