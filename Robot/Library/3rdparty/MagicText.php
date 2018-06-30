<?php
// MagicText Library(for CITADEL Framework) V.1 By NIMIX3.
// NOTE : PLEASE DO NOT EDIT or SELL This CODE FOR COMMERCIAL PURPOSE!
    class MagicText
    {
		public function GenerateImages($source,$destination,$color='',$font='arial.ttf',$text=' ',$size=20,$x=1,$y=1,$fa=true,$padding=20,$angle=0)
		{
			$image = ImageCreateFromJPEG($source);
			if(!isset($color) or empty($color))
				$color = imagecolorallocate($image, 0, 0, 0);
			else
				$color = imagecolorallocate($image, $color[0], $color[1], $color[2]);
			if(strpos($text,PHP_EOL) !== false)
			{
				$yy = $y;
				$text = explode(PHP_EOL,$text);
				foreach($text as $txt)
				{
					if($fa)
					{
						$txt = $this->farsi_correct(' '.$txt.' ');
						$xz = $this->AlignRight($x,$size,$font,' '.$txt.' ',$angle);
					}
					imagettftext($image,$size,$angle,$xz,$yy,$color,$font,$txt);
					$yy += $padding;
				}
			}
			else
			{
			if($fa)
			{
				$text = $this->farsi_correct(' '.$text.' ');
				$x = $this->AlignRight($x,$size,$font,' '.$text.' ',$angle);
			}
			imagettftext($image,$size,$angle,$x,$y,$color,$font,$text);
			}
			imagejpeg($image,$destination,100);
			imagedestroy($image);
		}
		
		private function AlignRight($x=0,$size=20,$font,$text,$angle=0)
		{
			$dimensions = imagettfbbox($size, $angle, $font, $text);
			$textWidth = abs($dimensions[4] - $dimensions[0]);
			$x = $x - $textWidth;
			return $x;
		}
		
		private function farsi_correct($string)
		{
			$len = mb_strlen($string, 'utf-8');
			$result = '';
			for ($i = ($len - 1); $i >= 0; $i--) {
				$result .= mb_substr($string, $i, 1, 'utf-8');
			}
			$spaces_after = array('', ' ', 'ا', 'آ', 'أ', 'إ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'و', 'ؤ', '?', '؟', ')', '(', '"', "'", '<', '>', '.','،');
			$spaces_before = array('', ' ', '?', '؟', ')', '(', '"', "'", '<', '>', '.','،');
			$chars = array();
			$chars[] = array('آ', 'ﺂ', 'آ', 'ﺂ');
			$chars[] = array('أ', 'ﺄ', 'ﺃ', 'ﺄ');
			$chars[] = array('إ', 'ﺈ', 'ﺇ', 'ﺈ');
			$chars[] = array('ا', 'ﺎ', 'ا', 'ﺎ');
			$chars[] = array('ب', 'ﺐ', 'ﺑ', 'ﺒ');
			$chars[] = array('پ', 'ﭗ', 'ﭘ', 'ﭙ');
			$chars[] = array('ت', 'ﺖ', 'ﺗ', 'ﺘ');
			$chars[] = array('ث', 'ﺚ', 'ﺛ', 'ﺜ');
			$chars[] = array('ج', 'ﺞ', 'ﺟ', 'ﺠ');
			$chars[] = array('چ', 'ﭻ', 'ﭼ', 'ﭽ');
			$chars[] = array('ح', 'ﺢ', 'ﺣ', 'ﺤ');
			$chars[] = array('خ', 'ﺦ', 'ﺧ', 'ﺨ');
			$chars[] = array('د', 'ﺪ', 'ﺩ', 'ﺪ');
			$chars[] = array('ذ', 'ﺬ', 'ﺫ', 'ﺬ');
			$chars[] = array('ر', 'ﺮ', 'ﺭ', 'ﺮ');
			$chars[] = array('ز', 'ﺰ', 'ﺯ', 'ﺰ');
			$chars[] = array('ژ', 'ﮋ', 'ﮊ', 'ﮋ');
			$chars[] = array('س', 'ﺲ', 'ﺳ', 'ﺴ');
			$chars[] = array('ش', 'ﺶ', 'ﺷ', 'ﺸ');
			$chars[] = array('ص', 'ﺺ', 'ﺻ', 'ﺼ');
			$chars[] = array('ض', 'ﺾ', 'ﺿ', 'ﻀ');
			$chars[] = array('ط', 'ﻂ', 'ﻃ', 'ﻄ');
			$chars[] = array('ظ', 'ﻆ', 'ﻇ', 'ﻈ');
			$chars[] = array('ع', 'ﻊ', 'ﻋ', 'ﻌ');
			$chars[] = array('غ', 'ﻎ', 'ﻏ', 'ﻐ');
			$chars[] = array('ف', 'ﻒ', 'ﻓ', 'ﻔ');
			$chars[] = array('ق', 'ﻖ', 'ﻗ', 'ﻘ');
			$chars[] = array('ک', 'ﻚ', 'ﻛ', 'ﻜ');
			$chars[] = array('ك', 'ﻚ', 'ﻛ', 'ﻜ');
			$chars[] = array('گ', 'ﮓ', 'ﮔ', 'ﮕ');
			$chars[] = array('ل', 'ﻞ', 'ﻟ', 'ﻠ');
			$chars[] = array('م', 'ﻢ', 'ﻣ', 'ﻤ');
			$chars[] = array('ن', 'ﻦ', 'ﻧ', 'ﻨ');
			$chars[] = array('و', 'ﻮ', 'ﻭ', 'ﻮ');
			$chars[] = array('ؤ', 'ﺆ', 'ﺅ', 'ﺆ');
			$chars[] = array('ی', 'ﯽ', 'ﯾ', 'ﯿ');
			$chars[] = array('ي', 'ﻲ', 'ﻳ', 'ﻴ');
			$chars[] = array('ئ', 'ﺊ', 'ﺋ', 'ﺌ');
			//$chars[] = array('ه', 'ﻪ', 'ﮬ', 'ﮭ');
			$chars[] = array('ه', 'ﻪ', 'ﻫ', 'ﻬ');
			$chars[] = array('ۀ', 'ﮥ', 'ﮬ', 'ﮭ');
			$chars[] = array('ة', 'ﺔ', 'ﺗ', 'ﺘ');
			$chars[] = array(' ', ' ', ' ', ' ');
			$chars[] = array('0', '0', '0', '0');
			$chars[] = array('1', '1', '1', '1');
			$chars[] = array('2', '2', '2', '2');
			$chars[] = array('3', '3', '3', '3');
			$chars[] = array('4', '4', '4', '4');
			$chars[] = array('5', '5', '5', '5');
			$chars[] = array('6', '6', '6', '6');
			$chars[] = array('7', '7', '7', '7');
			$chars[] = array('8', '8', '8', '8');
			$chars[] = array('9', '9', '9', '9');
			$chars[] = array('؟', '؟', '؟', '؟');
			//$chars[] = array('،', '، ', ' ،', '، ');
		
			$string = $result;
			$len = mb_strlen($string, 'utf-8');
			$result = array();
			$buffer = array();
		
			for ($i = 0; $i < $len; $i++) {
				$previous_char = $i > 0 ? mb_substr($string, $i - 1, 1, 'utf-8') : '';
				$current_char = mb_substr($string, $i, 1, 'utf-8');
				$next_char = $i < ($len - 1) ? mb_substr($string, $i + 1, 1, 'utf-8') : '';
		
				$in_array = false;
				foreach ($chars as $char) {
					if (in_array($current_char, $char)) {
						$in_array = true;
						if (!in_array($next_char, $spaces_after) && !in_array($previous_char, $spaces_before)) {
							if ($current_char == ' ') {
								if (!$this->in_farsi_array($chars, $next_char) && !$this->in_farsi_array($chars, $previous_char))
									$in_array = false;
								else
									$result[] = $char[3];
							} else
								$result[] = $char[3];
						} elseif (!in_array($previous_char, $spaces_before)) {
							$result[] = $char[2];
						} elseif (!in_array($next_char, $spaces_after)) {
							$result[] = $char[1];
						} else {
							$result[] = $char[0];
						}
						continue;
					}
				}
				if (!$in_array) {
					$buffer[] = $current_char;
				} else {
					$lastChar = array_pop($result);
					$result = array_merge($result, $buffer);
					$result[] = $lastChar;
					$buffer = array();
				}
				$in_array = false;
			}
		
			if (count($buffer))
				$result = array_merge($result, $buffer);
		
			return implode('', $result);
		}
		
		private function in_farsi_array(&$farsi, $text)
		{
			foreach ($farsi as $t) {
				if (in_array($text, $t))
					return true;
			}
			return false;
		}
	}
?>