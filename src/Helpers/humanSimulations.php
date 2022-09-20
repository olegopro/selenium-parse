<?php

namespace App\Helpers;

trait humanSimulations
{
	public function humanSleep($min = 1, $max = 5)
	{
		sleep(rand($min, $max));

		return $this;
	}

	protected function humanInputText($element, $text)
	{
		foreach (mb_str_split($text) as $item) {
			$element->sendKeys($item);
			sleep(rand(0, 1));
		}
	}

	protected function humanInputTextNumber($element, $text)
	{
		foreach (str_split($text) as $item) {
			$element->sendKeys($item);
			sleep(rand(0, 1));
		}
	}
}
