<?php

namespace App\SmsAPI;

use GuzzleHttp\ClientInterface;

interface ServiceInterface
{
	public function setClient(ClientInterface $client);

	public function setLogger($logger);

	public function getBalance();

	public function getNumber($service, $country);

	public function setReady($id);

	public function setUsed($id);

	public function setCancel($id);

	public function setComplete($id);

	public function getList();

	public function getStatus($id);
}
