<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации настроек виджета.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    // JPEG quality of CAPTCHA image (bigger is better quality, but larger file size)
    'jpegQuality' => 90,
    // CAPTCHA image colors (RGB, 0-255]
    'foregroundColor' => '#5B9676'/*[/*mt_rand(0,80], mt_rand(0,80], mt_rand(0,80]*]*/,
    'backgroundColor' => '#155551'/*[228, 234, 237]*/,
    //  set to false to remove credits line. Credits adds 12 pixels to image height
    'showCredits' => false,
    //'credits'     => $_SERVER['SERVER_NAME'],
    // increase safety by prevention of spaces between symbols
    'noSpaces' => true,
    // folder with fonts
    'fontsDir' => 'Fonts',
    // CAPTCHA image size (you do not need to change it, this parameters is optimal]
    'width'  => 150,
    'height' => 70,
    // CAPTCHA string length random 5 or 6 or 7
    'length' => '5-7',
    //mt_rand(5, 7],
    // KCAPTCHA configuration file (do not change without changing font files!)
    'alphabet' => "0123456789abcdefghijklmnopqrstuvwxyz",
    // symbols used to draw CAPTCHA (alphabet without similar symbols (o=0, 1=l, i=j, t=f))
    'allowedSymbols' => "23456789abcdegikpqsvxyz",
    // symbol's vertical fluctuation amplitude
    'fluctuationAmplitude' => 4,
    // noise no white noise
    'whiteNoiseDensity' => '1/10',
    // noise no white noise no black noise
    'blackNoiseDensity' => '1/70',
    // type file output
    'type' => 'PNG'
];
