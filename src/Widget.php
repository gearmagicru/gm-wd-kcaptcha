<?php
/**
 * Виджет веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Widget\KCaptcha;

use Gm;
use Gm\Helper\Html;
use Gm\View\WidgetResourceTrait;
use Gm\Widget\KCaptcha\Provider\KCaptcha;

/**
 * Виджет "KCaptcha".
 * 
 * Пример использования с менеджером виджетов:
 * ```php
 * $captcha = Gm::$app->widgets->get('gm.wd.kcaptcha', ['width' => 100, 'height' => 70]);
 * $captcha->run();
 * // вывод капчи в тег изображения
 * $captcha = Gm::$app->widgets->get('gm.wd.kcaptcha', ['toHtml' => true]);
 * $captcha->run();
 * // результат: <img src="data:image/png;base64..." />
 * ```
 * 
 * Пример использования в шаблоне:
 * ```php
 * echo $this->widget('gm.wd.kcaptcha', ['width' => 100, 'height' => 70]);
 * ```
 * 
 * Пример использования с namespace:
 * ```php
 * use Gm\Widget\KCaptcha\Widget as KCaptcha;
 * 
 * (new KCaptcha(['width' => 100, 'height' => 70]))->render();
 * ```
 * если namespace ранее не добавлен в PSR, необходимо выполнить:
 * ```php
 * Gm::$loader->addPsr4('Gm\Widget\KCaptcha\\', Gm::$app->modulePath . '/gm/gm.wd.kcaptcha/src');
 * ```
 * 
 * Пример с плохой реализацией:
 * ```php
 * use Gm\Widget\KCaptcha\Widget as KCaptcha;
 * 
 * echo KCaptcha::widget(['width' => 100, 'height' => 70]);
 * ```
 * т.к. используется буферизация вывода при рендере виджета. 
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Widget\KCaptcha
 * @since 1.0
 */
class Widget extends \Gm\View\Widget
{
    use WidgetResourceTrait;

    /**
     * @see Widget::getCaptcha()
     * 
     * @var KCaptcha
     */
    protected KCaptcha $captcha;

    /**
     * Возвращает Капчу.
     *
     * @return KCaptcha
     */
    public function getCaptcha(): KCaptcha
    {
        if (!isset($this->captcha)) {
            $this->captcha = new KCaptcha($this->getSettings());
            $this->captcha->generate();
        }
        return $this->captcha;
    }

    /**
     * {@inheritdoc}
     */
    public function run(): mixed
    {
        $captcha = $this->getCaptcha();

        Gm::$app->session->open();
        Gm::$app->session->set('kcaptcha', $captcha->getKey());

        // если необходимо использовать тег изображения
        if ($this->settings->toHtml)
            echo Html::img($captcha->renderEncode(), [], false);
        else
        if ($this->settings->toString)
            return $captcha->renderToString();
        else
            $captcha->render();
    }
}