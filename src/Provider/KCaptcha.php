<?php
/**
 * Этот файл является частью пакета GM Framework.
 * 
 * @link https://gearmagic.ru/framework/
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Widget\KCaptcha\Provider;

use GdImage;
use Gm\Config\Config;
use Gm\Helper\Image;

/**
 * KCaptcha быстрый и простой класс реализации Капчи.
 * 
 * @author Kruglov Sergey <kruglov@yandex.ru>
 * @package Gm\Captcha\KCaptcha
 * @version 2.0
 * @since 2.0
 */
class KCaptcha
{
   /**
     * Конфигуратор капчи.
     *
     * @var Config
     */
    protected Config $config;

   /**
     * Сгенерированный ключ.
     *
     * @var string
     */
    protected string $keyString = '';

    /**
	 * 
     * @var GdImage
     */
    protected GdImage $image;

    /**
     * Конструктор класса.
     * 
     * @param Gm\Config $config конфигурация капчи
	 * 
     * @return void
     */
    public function __construct(?Config $config = null)
    {
        if ($config === null)
            $this->config = new Config('.kcaptcha.php');
        else
            $this->config = $config;
    }

    /**
     * Возвращение списка шрифтов
     *
     * @return array
     */
    protected function getFonts(): array
    {
        $fonts = [];
        $fontsdir_absolute = dirname(__FILE__) . '/' . $this->config->get('fontsDir');
        if ($handle = opendir($fontsdir_absolute)) {
            while (false !== ($file = readdir($handle))) {
                if (preg_match('/\.png$/i', $file)) {
                    $fonts[]=$fontsdir_absolute.'/'.$file;
                }
            }
            closedir($handle);
        }

        return $fonts;
    }

    /**
     * Генерирование изображения капчи.
     *
     * @return void
     */
    public function generate(): void
    { 
        $foregroundColor      = Image::hexToRgb($this->config->foregroundColor);
        $backgroundColor      = Image::hexToRgb($this->config->backgroundColor);
        $showCredits          = $this->config->get('showСredits', false);
        $credits              = $this->config->get('credits');
        $noSpaces             = $this->config->get('noSpaces');
        $width                = $this->config->get('width');
        $height               = $this->config->get('height');
        $alphabet             = $this->config->get('alphabet');
        $allowedSymbols       = $this->config->get('allowedSymbols');
        $fluctuationAmplitude = $this->config->get('fluctuationAmplitude');
        // length
        if (is_string($this->config->length)) {
            $div = explode('-', $this->config->length);
            $this->config->length = rand($div[0], $div[1]);
        }
        $length = $this->config->length;
        // whiteNoiseDensity
        if (is_string($this->config->whiteNoiseDensity)) {
            $div = explode('/', $this->config->whiteNoiseDensity);
            $this->config->whiteNoiseDensity = $div[0] / $div[1];
        }
        $whiteNoiseDensity = $this->config->whiteNoiseDensity;
        // blackNoiseDensity
        if (is_string($this->config->blackNoiseDensity)) {
            $div = explode('/', $this->config->blackNoiseDensity);
            $this->config->blackNoiseDensity = $div[0] / $div[1];
        }
        $blackNoiseDensity = $this->config->blackNoiseDensity;
        $fonts = $this->getFonts();
        $alphabetLength = strlen($alphabet);

        do{
			// generating random keyString
			while (true){
				$this->keyString = '';
				for ($i = 0; $i < $length; $i++){
					$this->keyString .= $allowedSymbols[mt_rand(0,strlen($allowedSymbols)-1)] ?? '';
				}
				if(!preg_match('/cp|cb|ck|c6|c9|rn|rm|mm|co|do|cl|db|qp|qb|dp|ww/', $this->keyString)) break;
			}
		
			$font_file=$fonts[mt_rand(0, count($fonts)-1)];
			$font=imagecreatefrompng($font_file);
			imagealphablending($font, true);

			$fontfile_width=imagesx($font);
			$fontfile_height=imagesy($font)-1;
			
			$font_metrics=array();
			$symbol=0;
			$reading_symbol=false;

			// loading font
			for($i=0;$i<$fontfile_width && $symbol<$alphabetLength;$i++){
				$transparent = (imagecolorat($font, $i, 0) >> 24) == 127;

				if(!$reading_symbol && !$transparent){
					$font_metrics[$alphabet[$symbol]]=array('start'=>$i);
					$reading_symbol=true;
					continue;
				}

				if($reading_symbol && $transparent){
					$font_metrics[$alphabet[$symbol]]['end']=$i;
					$reading_symbol=false;
					$symbol++;
					continue;
				}
			}

            $img = imagecreatetruecolor($width, $height);
			imagealphablending($img, true);
			$white=imagecolorallocate($img, 255, 255, 255);
			$black=imagecolorallocate($img, 0, 0, 0);

			imagefilledrectangle($img, 0, 0, $width-1, $height-1, $white);

			// draw text
			$x=1;
			$odd=mt_rand(0,1);
			if($odd==0) $odd=-1;
			for($i=0;$i<$length;$i++){
				$m=$font_metrics[$this->keyString[$i]];

				$y=(($i%2)*$fluctuationAmplitude - $fluctuationAmplitude/2)*$odd
					+ mt_rand(-round($fluctuationAmplitude/3), round($fluctuationAmplitude/3))
					+ ($height-$fontfile_height)/2;

				if($noSpaces){
					$shift=0;
					if($i>0){
						$shift=10000;
						for($sy=3;$sy<$fontfile_height-10;$sy+=1){
							for($sx=$m['start']-1;$sx<$m['end'];$sx+=1){
				        		$rgb=imagecolorat($font, $sx, $sy);
				        		$opacity=$rgb>>24;
								if($opacity<127){
									$left=$sx-$m['start']+$x;
									$py=$sy+$y;
									if($py>$height) break;
									for ($px = min($left,$width - 1); $px > $left - 200 && $px >= 0; $px -= 1){
						        		$color = @imagecolorat($img, $px, $py) & 0xff;
										if($color+$opacity<170){ // 170 - threshold
											if($shift>$left-$px){
												$shift=$left-$px;
											}
											break;
										}
									}
									break;
								}
							}
						}
						if ($shift==10000){
							$shift = mt_rand(4,6);
						}

					}
				}else {
					$shift = 1;
				}
				@imagecopy($img, $font, $x - $shift, $y, $m['start'], 1, $m['end'] - $m['start'], $fontfile_height);
				$x += $m['end'] - $m['start'] - $shift;
			}
		}while($x>=$width-10); // while not fit in canvas
		
		//noise
		$white=imagecolorallocate($font, 255, 255, 255);
		$black=imagecolorallocate($font, 0, 0, 0);
		for($i=0;$i<(($height-30)*$x)*$whiteNoiseDensity;$i++){
			imagesetpixel($img, mt_rand(0, $x-1), mt_rand(10, $height-15), $white);
		}
		for($i=0;$i<(($height-30)*$x)*$blackNoiseDensity;$i++){
			imagesetpixel($img, mt_rand(0, $x-1), mt_rand(10, $height-15), $black);
		}

		$center=$x/2;
		// credits. To remove, see configuration file
		$img2=imagecreatetruecolor($width, $height+($showCredits ? 12 : 0));
		$foreground=imagecolorallocate($img2, $foregroundColor[0], $foregroundColor[1], $foregroundColor[2]);
		$background=imagecolorallocate($img2, $backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
		// Выставляем цвет $black как прозрачный
        imagecolortransparent($img2, $background);
        imagefilledrectangle($img2, 0, 0, $width-1, $height-1, $background);	
		imagefilledrectangle($img2, 0, $height, $width-1, $height+12, $foreground);

		$credits=empty($credits)?$_SERVER['HTTP_HOST']:$credits;
		imagestring($img2, 2, $width/2-imagefontwidth(2)*strlen($credits)/2, $height-2, $credits, $background);

		// periods
		$rand1=mt_rand(750000,1200000)/10000000;
		$rand2=mt_rand(750000,1200000)/10000000;
		$rand3=mt_rand(750000,1200000)/10000000;
		$rand4=mt_rand(750000,1200000)/10000000;
		// phases
		$rand5=mt_rand(0,31415926)/10000000;
		$rand6=mt_rand(0,31415926)/10000000;
		$rand7=mt_rand(0,31415926)/10000000;
		$rand8=mt_rand(0,31415926)/10000000;
		// amplitudes
		$rand9=mt_rand(330,420)/110;
		$rand10=mt_rand(330,450)/100;

		//wave distortion
		for($x=0;$x<$width;$x++){
			for($y=0;$y<$height;$y++){
				$sx=$x+(sin($x*$rand1+$rand5)+sin($y*$rand3+$rand6))*$rand9-$width/2+$center+1;
				$sy=$y+(sin($x*$rand2+$rand7)+sin($y*$rand4+$rand8))*$rand10;

				if($sx<0 || $sy<0 || $sx>=$width-1 || $sy>=$height-1){
					continue;
				}else{
					$color = @imagecolorat($img, $sx, $sy) & 0xFF;
					$color_x = @imagecolorat($img, $sx+1, $sy) & 0xFF;
					$color_y = @imagecolorat($img, $sx, $sy+1) & 0xFF;
					$color_xy = @imagecolorat($img, $sx+1, $sy+1) & 0xFF;
				}

				if($color==255 && $color_x==255 && $color_y==255 && $color_xy==255){
					continue;
				}else if($color==0 && $color_x==0 && $color_y==0 && $color_xy==0){
					$newred=$foregroundColor[0];
					$newgreen=$foregroundColor[1];
					$newblue=$foregroundColor[2];
				}else{
					$frsx=$sx-floor($sx);
					$frsy=$sy-floor($sy);
					$frsx1=1-$frsx;
					$frsy1=1-$frsy;

					$newcolor=(
						$color*$frsx1*$frsy1+
						$color_x*$frsx*$frsy1+
						$color_y*$frsx1*$frsy+
						$color_xy*$frsx*$frsy);

					if($newcolor>255) $newcolor=255;
					$newcolor=$newcolor/255;
					$newcolor0=1-$newcolor;

					$newred=$newcolor0*$foregroundColor[0]+$newcolor*$backgroundColor[0];
					$newgreen=$newcolor0*$foregroundColor[1]+$newcolor*$backgroundColor[1];
					$newblue=$newcolor0*$foregroundColor[2]+$newcolor*$backgroundColor[2];
				}

				imagesetpixel($img2, $x, $y, @imagecolorallocate($img2, $newred, $newgreen, $newblue));
			}
		}
        $this->image = $img2;
    }

    /**
     * Возвращает тип изображения.
     *
     * @return string
     */
	public function getType(): string
	{
		return $this->config->type ? strtolower($this->config->type) : '';
	}

    /**
     * Вывод капчи.
     *
     * @return void
     */
    public function render(): void
    {
        $type = $this->getType();
        if (empty($type)) return;

        switch ($type) {
            case 'gif':
                header('Content-Type: image/gif');
                imagegif($this->image);
                break;

            case 'jpeg':
                header('Content-Type: image/jpeg');
                imagejpeg($this->image, null, $this->config->jpegQuality ?: 90);
                break;

            case 'png':
                header('Content-Type: image/png');
                imagepng($this->image);
                break;
        }
    }

    /**
     * Вывод изображения капчи строку.
     *
     * @return string
     */
	public function renderToString(): string
	{
        $type = $this->getType();
        if (empty($type)) return '';

		ob_start();
        switch ($type) {
            case 'gif': imagegif($this->image); break;

            case 'jpeg': imagejpeg($this->image, null, $this->config->jpegQuality ?: 90); break;

            case 'png': imagepng($this->image); break;
        }
        $content = ob_get_contents();
        ob_end_clean();

		return $content ? $content : '';
	}

    /**
     * Вывод изображения в кодироваке base64.
     *
     * @return string
     */
    public function renderEncode(): string
    {
        $type = $this->getType();
        if (empty($type)) return '';

        ob_start();
        switch ($type) {
            case 'gif': imagegif($this->image); break;

            case 'jpeg': imagejpeg($this->image, null, $this->config->jpegQuality ?: 90); break;

            case 'png': imagepng($this->image); break;
        }
        $content = ob_get_contents();
        ob_end_clean();

        if ($content)
            return 'data:image/' . $type . ';base64,' . base64_encode($content);
        else
            return '';
    }

    /**
     * Возвращает сгенерированный ключ капчи.
     *
     * @return string
     */
    function getKey(): string
    {
        return $this->keyString;
    }
}
