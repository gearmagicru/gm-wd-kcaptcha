<?php
/**
 * Виджет веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Widget\KCaptcha\Model;

use Gm\Panel\Data\Model\WidgetSettingsModel;

/**
 * Модель настроек виджета.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Widget\KCaptcha\Model
 * @since 1.0
 */
class Settings extends WidgetSettingsModel
{
    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            // JPEG quality of CAPTCHA image (bigger is better quality, but larger file size)
            'jpegQuality' => 'jpegQuality',
            // CAPTCHA image colors (RGB, 0-255]
            'foregroundColor' => 'foregroundColor'/*[/*mt_rand(0,80], mt_rand(0,80], mt_rand(0,80]*]*/,
            'backgroundColor' => 'backgroundColor'/*[228, 234, 237]*/,
            //  set to false to remove credits line. Credits adds 12 pixels to image height
            'showCredits' => 'showCredits',
            //'credits'     => $_SERVER['SERVER_NAME'],
            // increase safety by prevention of spaces between symbols
            'noSpaces' => 'noSpaces',
            // folder with fonts
            'fontsDir' => 'fontsDir',
            // CAPTCHA image size (you do not need to change it, this parameters is optimal]
            'width'  => 'width',
            'height' => 'height',
            // CAPTCHA string length random 5 or 6 or 7
            'length' => 'length',
            //mt_rand(5, 7],
            // KCAPTCHA configuration file (do not change without changing font files!)
            'alphabet' => "alphabet",
            // symbols used to draw CAPTCHA (alphabet without similar symbols (o=0, 1=l, i=j, t=f))
            'allowedSymbols' => "allowedSymbols",
            // symbol's vertical fluctuation amplitude
            'fluctuationAmplitude' => 'fluctuationAmplitude',
            // noise no white noise
            'whiteNoiseDensity' => 'whiteNoiseDensity',
            // noise no white noise no black noise
            'blackNoiseDensity' => 'blackNoiseDensity',
            // type file output
            'type' => 'type'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'jpegQuality'          => 'JPEG quality',
            'foregroundColor'      => 'Foreground color',
            'backgroundColor'      => 'Background color',
            'showCredits'          => 'Credits line',
            'noSpaces'             => 'No spaces',
            'fontsDir'             => 'Fonts dir',
            'width'                => 'Width',
            'height'               => 'Height',
            'length'               => 'String length',
            'alphabet'             => "Alphabet",
            'allowedSymbols'       => "Allowed symbols",
            'fluctuationAmplitude' => 'Fluctuation amplitude',
            'whiteNoiseDensity'    => 'No white noise',
            'blackNoiseDensity'    => 'No black noise',
            'type'                 => 'Type image'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function formatterRules(): array
    {
        return [
            [['showCredits', 'noSpaces'], 'logic' => [true, false]]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function validationRules(): array
    {
        return [

            [
                ['jpegQuality', 'foregroundColor', 'backgroundColor', 'fontsDir', 'width', 
                 'height', 'length', 'alphabet', 'allowedSymbols', 'whiteNoiseDensity', 'blackNoiseDensity',
                 'type'], 'notEmpty'],
            // качество JPEG
            [
                'jpegQuality',
                'between',
                'max' => 100, 'type' => 'int'
            ],
            // ширина
            [
                'width',
                'between',
                'max' => 200, 'type' => 'int'
            ],
            // высота
            [
                'height',
                'between',
                'max' => 200, 'type' => 'int'
            ],
        ];
    }
}