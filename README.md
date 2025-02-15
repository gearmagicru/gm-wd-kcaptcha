# <img src="https://raw.githubusercontent.com/gearmagicru/gm-wd-kcaptcha/refs/heads/main/assets/images/icon.svg" width="64px" height="64px" align="absmiddle"> Виджет капчи "KCaptcha"

[![Latest Stable Version](https://img.shields.io/packagist/v/gearmagicru/gm-wd-kcaptcha.svg)](https://packagist.org/packages/gearmagicru/gm-wd-kcaptcha)
[![Total Downloads](https://img.shields.io/packagist/dt/gearmagicru/gm-wd-kcaptcha.svg)](https://packagist.org/packages/gearmagicru/gm-wd-kcaptcha)
[![Author](https://img.shields.io/badge/author-anton.tivonenko@gmail.com-blue.svg)](mailto:anton.tivonenko@gmail)
[![Source Code](https://img.shields.io/badge/source-gearmagicru/gm--wd--kcaptcha-blue.svg)](https://github.com/gearmagicru/gm-wd-kcaptcha)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/gearmagicru/gm-wd-kcaptcha/blob/master/LICENSE)
![Component type: widget](https://img.shields.io/badge/component%20type-widget-green.svg)
![Component ID: gm-wd-kcaptcha](https://img.shields.io/badge/component%20id-gm.wd.kcaptcha-green.svg)
![php 8.2+](https://img.shields.io/badge/php-min%208.2-red.svg)

Виджет предназначен для защиты от автоматического спама с помощью KCaptcha.

## Пример применения
### с менеджером виджетов:
```
$captcha = Gm::$app->widgets->get('gm.wd.kcaptcha', ['width' => 100, 'height' => 70]);
$captcha->run();
```
### в шаблоне:
```
echo $this->widget('gm.wd.kcaptcha', ['width' => 100, 'height' => 70]);
```
### с namespace:
```
use Gm\Widget\KCaptcha\Widget as KCaptcha;
(new KCaptcha(['width' => 100, 'height' => 70]))->render();
```
если namespace ранее не добавлен в PSR, необходимо выполнить:
```
Gm::$loader->addPsr4('Gm\Widget\KCaptcha\\', Gm::$app->modulePath . '/gm/gm.wd.kcaptcha/src');
```
### вывод в тег изображения:
```
$captcha = Gm::$app->widgets->get('gm.wd.kcaptcha', ['toHtml' => true]);
$captcha->run();
// результат: <img src="data:image/png;base64..." />
```
### с плохой реализацией:
```
use Gm\Widget\KCaptcha\Widget as KCaptcha;
echo KCaptcha::widget(['width' => 100, 'height' => 70]);
```
т.к. используется буферизация вывода при рендере виджета. 

## Установка

Для добавления виджета в ваш проект, вы можете просто выполнить команду ниже:

```
$ composer require gearmagicru/gm-wd-kcaptcha
```

или добавить в файл composer.json вашего проекта:
```
"require": {
    "gearmagicru/gm-wd-kcaptcha": "*"
}
```

После добавления виджета в проект, воспользуйтесь Панелью управления GM Panel для установки его в редакцию вашего веб-приложения.
