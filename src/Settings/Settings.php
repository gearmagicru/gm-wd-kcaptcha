<?php
/**
 * Виджет веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Widget\KCaptcha\Settings;

use Gm\Panel\Widget\SettingsWindow;

/**
 * Настройки виджета.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Widget\KCaptcha\Settings
 * @since 1.0
 */
class Settings extends SettingsWindow
{
    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        $this->responsiveConfig = [
            'height < 700' => ['height' => '99%'],
            'width < 500' => ['width' => '99%'],
        ];
        $this->width = 500;
        $this->form->autoScroll = true;
        $this->form->defaults = [
            'labelWidth' => 180,
            'labelAlign' => 'right'
        ];
        $this->form->items = [
            [
                'xtype'      => 'textfield',
                'fieldLabel' => '#JPEG quality',
                'tooltip'    => '#JPEG quality of CAPTCHA image (bigger is better quality, but larger file size)',
                'name'       => 'jpegQuality',
                'maxLength'  => 50,
                'width'      => 270,
                'allowBlank' => true
            ],
            [
                'xtype'      => 'textfield',
                'fieldLabel' => '#Foreground color',
                'name'       => 'foregroundColor',
                'width'      => 270,
                'allowBlank' => false
            ],
            [
                'xtype'      => 'textfield',
                'fieldLabel' => '#Background color',
                'name'       => 'backgroundColor',
                'width'      => 270,
                'allowBlank' => false
            ],
            [
                'xtype'      => 'combobox',
                'fieldLabel' => '#Type image',
                'name'       => 'type',
                'store'      => [
                    'fields' => ['value', 'type'],
                    'data'   => [['PNG', 'PNG'], ['JPEG', 'JPEG'], ['GIF', 'GIF']]
                ],
                'displayField' => 'type',
                'valueField'   => 'value',
                'queryMode'    => 'local',
                'editable'     => false,
                'width'        => 270,
                'allowBlank' => false
            ],
            [
                'xtype'      => 'checkbox',
                'ui'         => 'switch',
                'name'       => 'showCredits',
                'fieldLabel' => '#Credits line',
                'autoEl'     => [
                    'tag'       => 'div',
                    'data-qtip' => '#Set to false to remove credits line. Credits adds 12 pixels to image height'
                ]
            ],
            [
                'xtype'      => 'checkbox',
                'ui'         => 'switch',
                'name'       => 'noSpaces',
                'fieldLabel' => '#No spaces',
                'autoEl'     => [
                    'tag'       => 'div',
                    'data-qtip' => '#Increase safety by prevention of spaces between symbols'
                ]
            ],
            [
                'xtype'      => 'textfield',
                'fieldLabel' => '#Fonts dir',
                'name'       => 'fontsDir',
                'width'      => '100%',
                'allowBlank' => false
            ],
            [
                'xtype'      => 'textfield',
                'fieldLabel' => '#String length',
                'name'       => 'length',
                'width'      => 270,
                'allowBlank' => false
            ],
            [
                'xtype'      => 'textfield',
                'fieldLabel' => '#Alphabet',
                'name'       => 'alphabet',
                'width'      => '100%',
                'allowBlank' => false
            ],
            [
                'xtype'      => 'textfield',
                'fieldLabel' => '#Allowed symbols',
                'tooltip'    => '#Symbols used to draw CAPTCHA',
                'name'       => 'allowedSymbols',
                'width'      => '100%',
                'allowBlank' => false
            ],
            [
                'xtype'      => 'textfield',
                'fieldLabel' => '#Fluctuation amplitude',
                'tooltip'    => '#Symbols vertical fluctuation amplitude',
                'name'       => 'fluctuationAmplitude',
                'width'      => 270,
                'allowBlank' => false
            ],
            [
                'xtype'      => 'textfield',
                'fieldLabel' => '#No white noise',
                'name'       => 'whiteNoiseDensity',
                'width'      => 270,
                'allowBlank' => false
            ],
            [
                'xtype'      => 'textfield',
                'fieldLabel' => '#No black noise',
                'name'       => 'blackNoiseDensity',
                'width'      => 270,
                'allowBlank' => false
            ],
            [
                'xtype'    => 'fieldset',
                'title'    => '#Sizes',
                'defaults' => [
                    'labelWidth' => 135,
                    'labelAlign' => 'right',
                    'allowBlank' => false,
                    'maxLength'  => 5,
                    'width'      => 210
                ],
                'items' => [
                    [
                        'xtype'      => 'textfield',
                        'fieldLabel' => '#Width',
                        'name'       => 'width'
                    ],
                    [
                        'xtype'      => 'textfield',
                        'fieldLabel' => '#Height',
                        'name'       => 'height'
                    ]
                ]
            ]
        ];
    }
}