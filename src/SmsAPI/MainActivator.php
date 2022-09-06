<?php

namespace App\SmsAPI;

use GuzzleHttp\ClientInterface;

class MainActivator
{
	/**
	 * @var ServiceInterface
	 */
	protected $service;

	/**
	 * MainActivator constructor
	 *
	 * @param ServiceInterface $service
	 * @param ClientInterface $client
	 * @param null $logger
	 */
	public function __construct(ServiceInterface $service, ClientInterface $client, $logger = null)
	{
		$this->service = $service;
		$this->service->setClient($client);

		if (isset($logger)) {
			$this->service->setLogger($logger);
		}
	}

	/**
	 * @return ServiceInterface
	 */
	public function getService()
	{
		return $this->service;
	}

	/**
	 * @param ServiceInterface $service
	 */
	public function setService(ServiceInterface $service)
	{
		$this->service = $service;
	}

	public function getBalance()
	{
		return $this->getService()->getBalance();
	}

	public function getNumber($service, $country)
	{
		return $this->getService()->getNumber($service, $country);
	}

	public function setReady($id)
	{
		return $this->getService()->setReady($id);
	}

	public function setUsed($id)
	{
		return $this->getService()->setUsed($id);
	}

	public function setCancel($id)
	{
		return $this->getService()->setCancel($id);
	}

	public function setComplete($id)
	{
		return $this->getService()->setComplete($id);
	}

	public function getList()
	{
		return $this - $this->getService()->getList();
	}

	public function getStatus($id)
	{
		return $this->getService()->getStatus($id);
	}
}
