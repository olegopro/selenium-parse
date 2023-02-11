<?php

namespace App\Projects\WebWalker\SalambaRu\Pages;

use Exception;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverExpectedCondition;

class FrontPage extends Page
{
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

        echo 'Ищем статьи на главной: ' . __FUNCTION__ . '()' . PHP_EOL;

        try {

            $elements = $this->driver->findElements(WebDriverBy::xpath(
                "//section[contains(@class,'category-section')]//div[contains(@class, 'post-entry-1')]"
            ));

            $rand = array_rand($elements);
            $randomElement = $elements[$rand];

            $randomElementUrlText = $randomElement->findElement(WebDriverBy::xpath("./h3/a"))->getText();
            echo $randomElementUrlText . PHP_EOL;

            $randomElementImage = $randomElement->findElement(WebDriverBy::xpath(".//img"));

            $toggle = true;
            while ($toggle) {
                try {

                    sleep(2);

                    $this->driver->action()->moveToElement($randomElementImage)->perform();
                    $randomElementImage->click();

                    $toggle = false;

                } catch (Exception $exception) {

                    //Удаляю версию chrome из сообщения
                    $message = preg_replace('/(\s*)(\(.*\))(\s*)/', '', $exception->getMessage());
                    echo __FUNCTION__ . '() - ' . $message . PHP_EOL;

                    $this->oneScrollDown();

                }
            }

        } catch (Exception $e) {

            echo __FUNCTION__ . 'Элементов не найдено' . PHP_EOL;
            echo __FUNCTION__ . 'Exception: ' . $e->getMessage() . PHP_EOL;

        }

        return $this;
    }

    public function readArticle($startHeight)
    {
        $bodyScrollHeight = $this->driver->executeScript("return document.body.scrollHeight");

        $innerHeight = $this->driver->executeScript("return window.innerHeight + window.scrollY") + 500;
        $offsetHeight = $this->driver->executeScript("return document.body.offsetHeight");

        while ($startHeight < $offsetHeight) {

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

            echo $randomElement->findElement(WebDriverBy::xpath(".//span[contains(@class, 'text-lines-1')]"))->getText() . PHP_EOL;

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

            $this->readArticle($this->driver->executeScript("return window.innerHeight + window.scrollY"));
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
}
