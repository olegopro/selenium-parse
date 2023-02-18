<?php

namespace App\Projects\WebWalker\SalambaRu\Example;

use App\GoLogin;
use Exception;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverDimension;

class GoLoginProfile
{

    public GoLogin $gl;

    public function __construct()
    {
        if (strtolower(PHP_OS) == 'linux') {
            putenv("WEBDRIVER_CHROME_DRIVER=./chromedriver");
        } elseif (strtolower(PHP_OS) == 'darwin') {
            putenv("WEBDRIVER_CHROME_DRIVER=/Users/evilgazz/Downloads/chromedriver109");
        } elseif (strtolower(PHP_OS) == 'winnt') {
            putenv("WEBDRIVER_CHROME_DRIVER=chromedriver.exe");
        }

        $this->gl = new GoLogin([
            'token'  => $_ENV['TOKEN'],
            'tmpdir' => __DIR__ . '/temp'
        ]);
    }

    public function createProfile($proxyData = [])
    {

        $osList = [
            'win' => [
                'os'       => 'win',
                'platform' => 'Win32'
            ],

            'mac' => [
                'os'       => 'mac',
                'platform' => 'MacIntel'
            ],

            'lin' => [
                'os'       => 'lin',
                'platform' => 'Linux x86_64'
            ]
        ];

        $selector = rand(1, 100);
        $os = [];

        switch (true) {
            case ($selector < 70):
                $os = $osList['win'];
                break;
            case ($selector <= 90):
                $os = $osList['mac'];
                break;
            default:
                $os = $osList['lin'];
                break;
        }

        try {
            return
                $profile_id = $this->gl->create([
                        'name'      => 'profile-' . $os['os'],
                        'os'        => $os['os'],
                        'navigator' => [
                            'language'   => 'ru-RU',
                            'userAgent'  => 'random',
                            'resolution' => 'random',
                            'platform'   => $os['platform']
                        ],

                        // 'proxyEnabled' => true,
                        // 'proxy'        => [
                        //     'mode' => 'none',
                        //     // 'autoProxyRegion' => 'us'
                        //     // 'host'            => '',
                        //     // 'port'            => '',
                        //     // 'username'        => '',
                        //     // 'password'        => '',
                        // ],

                        'webRTC' => [
                            'mode'    => 'alerted',
                            'enabled' => true
                        ],

                        ...$proxyData

                    ]
                );
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return null;
    }

    public function setOrbitaBrowser($profile_id)
    {
        return (new GoLogin([
            'token'        => $_ENV['TOKEN'],
            'profile_id'   => $profile_id,
            'port'         => GoLogin::getRandomPort(),
            'extra_params' => ['--lang=ru']
        ]));
    }

    public function runOrbitaBrowser($debugger_address)
    {
        var_dump($debugger_address) . PHP_EOL;

        $chromeOptions = new ChromeOptions();
        $chromeOptions->setExperimentalOption('debuggerAddress', $debugger_address);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);

        $driver = ChromeDriver::start($capabilities);
        $driver->manage()->window()->maximize();

        $getWindowSize = $driver->manage()->window()->getSize();
        $height = $getWindowSize->getHeight();
        $width = $getWindowSize->getWidth();

        $driver->manage()->window()->setSize(new WebDriverDimension($width, $height - rand(40, 120)));

        sleep(1);

        return $driver;
    }
}
