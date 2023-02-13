<?php

namespace App\Projects\WebWalker\SalambaRu\Pages;

use Exception;
use Facebook\WebDriver\Exception\ElementClickInterceptedException;
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\WebDriver;
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

    public function openFromVk($url)
    {
        $this->driver->get($url);
        $this->waitAfterLoad(3);

        $this->closeAuthModal();

        $this->scrollDown(5);
        $elements = $this->driver->findElements(WebDriverBy::xpath("//a[contains(@class, 'media_link__media')]"));
        echo __FUNCTION__ . '() - ' . 'Собираем все ссылки на посты' . PHP_EOL;

        $rand = array_rand($elements);
        $randomElement = $elements[$rand];

        sleep(rand(1, 10));

        try {

            $this->driver->action()->moveToElement($randomElement)->perform();
            $randomElement->click();

        } catch (Exception $exception) {

            echo $exception->getMessage();

            $this->driver->executeScript("arguments[0].scrollIntoView(true);", [$randomElement]);

            sleep(rand(1, 5));
            $this->driver->action()->moveToElement($randomElement)->perform();
            $randomElement->click();

        }

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

                    $this->scrollDown();

                }
            }

        } catch (Exception $e) {

            echo __FUNCTION__ . 'Элементов не найдено' . PHP_EOL;
            echo __FUNCTION__ . 'Exception: ' . $e->getMessage() . PHP_EOL;

        }

        return $this;
    }

    public function readArticle($startHeight = 0)
    {
        $this->driver->switchTo()->window($this->driver->getWindowHandles()[1]);
        $this->waitAfterLoad(3);

        $bodyHeight = $this->driver->executeScript('return document.body.scrollHeight');
        $innerHeight = $this->driver->executeScript('return window.innerHeight + window.scrollY');

        while ($startHeight < $bodyHeight) {

            $randScrollLength = rand(100, 600);
            $startHeight += $randScrollLength;

            $scrollReverse = rand(1, 100);

            if ($scrollReverse > 90) {
                $direction = '-';
                $startHeight -= $randScrollLength * 1.5;
            } else {
                $direction = null;
            }

            $this->driver->executeScript('window.scrollBy(0,' . $direction . $randScrollLength . ')');

            // echo '--startHeight--';
            // var_dump($startHeight) . PHP_EOL;
            // echo '--bodyScrollHeight--';
            // var_dump($bodyHeight) . PHP_EOL;
            // echo '--innerHeight--';
            // var_dump($innerHeight) . PHP_EOL;

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
                $this->scrollDown();

            }

            usleep(mt_rand(500000, 3000000));

            $this->readArticle($this->driver->executeScript("return window.innerHeight + window.scrollY"));
        }

        return $this;
    }

    private function scrollDown($count = 1)
    {
        $innerCount = 0;
        $height = 0;

        while ($count > $innerCount) {
            usleep(mt_rand(200000, 3000000));

            $randScrollLength = rand(200, 800);
            $height += $randScrollLength;
            $this->driver->executeScript("window.scrollBy(0," . $height . ")");

            $innerCount++;
        }
    }

    private function closeAuthModal()
    {
        try {

            echo __FUNCTION__ . '() - ' . 'Ищем модальное окно авторизации' . PHP_EOL;
            $modalCloseButton = $this->driver->findElement(WebDriverBy::xpath("//button[contains(@class, 'UnauthActionBox__close')]"));

            sleep(rand(1, 5));
            $this->driver->action()->moveToElement($modalCloseButton)->perform();
            $modalCloseButton->click();
            echo __FUNCTION__ . '() - ' . 'Закрываем модальное авторизации' . PHP_EOL;

        } catch (Exception $exception) {

            echo __FUNCTION__ . '() - ' . 'Модальное окно не найдено' . PHP_EOL;

            $this->scrollDown();
            echo __FUNCTION__ . '() - ' . 'Скролим вниз' . PHP_EOL;

            $this->closeAuthModal();

        }

    }
}
