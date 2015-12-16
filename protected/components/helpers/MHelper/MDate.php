<?php

/**
 * Класс-хелпер для работы с датами
 *
 * @version 0.1 21.08.2011
 * @author webmaxx <webmaxx@webmaxx.name>
 */
class MDate extends MHelperBase
{
   
		// Сколько прошло времени от настоящего момента
            public static function TimeAgo0($time = null)
            {
              $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
              $periods_plural = array("seconds", "minutes", "hours", "days", "weeks", "months", "years", "decades");  
              $lengths = array("60","60","24","7","4.35","12","10");    

              $difference = time() - strtotime($time);    

              for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++)   
                  $difference /= $lengths[$j];    

              return Yii::t('server', "{n} $periods[$j] ago|{n} $periods_plural[$j] ago", round($difference)); 
            }
	
	
	/**
	 * Возвращает строку с относительным временем
	 * 
	 * @param integer $timestamp
	 * @return string
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public static function timeAgo($datetime=null, $options=array(), $nowt = null)
	{
		if(!$nowt)
			$now   = time();
		else 
			$now = $nowt;
		$inSeconds = is_int($datetime) ? $datetime : strtotime($datetime);
		$backwards = ($inSeconds > $now);

		$format = 'd.m.Y';
		$end    = '';
		$short  = false;

		if (is_array($options)) {
			if (isset($options['format'])) {
				$format = $options['format'];
				unset($options['format']);
			}
			if (isset($options['end'])) {
				$end = $options['end'];
				unset($options['end']);
			}
			if (isset($options['short'])) {
				$short = $options['short'];
				unset($options['short']);
			}
		} else {
			$short = $options;
                        var_dump($short);
		}

		if ($backwards) {
			$futureTime = $inSeconds;
			$pastTime = $now;
		} else {
			$futureTime = $now;
			$pastTime = $inSeconds;
		}
		$diff = $futureTime - $pastTime;

		// Если больше недели, то принимаем в расчет длину месяцев
		if ($diff >= 604800) {
			$current = array();
			$date = array();

			list($future['H'], $future['i'], $future['s'], $future['d'], $future['m'], $future['Y']) = explode('/', date('H/i/s/d/m/Y', $futureTime));

			list($past['H'], $past['i'], $past['s'], $past['d'], $past['m'], $past['Y']) = explode('/', date('H/i/s/d/m/Y', $pastTime));
			$years = $months = $weeks = $days = $hours = $minutes = $seconds = 0;

			if ($future['Y'] == $past['Y'] && $future['m'] == $past['m']) {
				$months = 0;
				$years = 0;
			} else {
				if ($future['Y'] == $past['Y']) {
					$months = $future['m'] - $past['m'];
				} else {
					$years = $future['Y'] - $past['Y'];
					$months = $future['m'] + ((12 * $years) - $past['m']);

					if ($months >= 12) {
						$years = floor($months / 12);
						$months = $months - ($years * 12);
					}

					if ($future['m'] < $past['m'] && $future['Y'] - $past['Y'] == 1) {
						$years --;
					}
				}
			}

			if ($future['d'] >= $past['d']) {
				$days = $future['d'] - $past['d'];
			} else {
				$daysInPastMonth = date('t', $pastTime);
				$daysInFutureMonth = date('t', mktime(0, 0, 0, $future['m'] - 1, 1, $future['Y']));

				if (!$backwards) {
					$days = ($daysInPastMonth - $past['d']) + $future['d'];
				} else {
					$days = ($daysInFutureMonth - $past['d']) + $future['d'];
				}

				if ($future['m'] != $past['m']) {
					$months --;
				}
			}

			if ($months == 0 && $years >= 1 && $diff < ($years * 31536000)) {
				$months = 11;
				$years --;
			}

			if ($months >= 12) {
				$years = $years + 1;
				$months = $months - 12;
			}

			if ($days >= 7) {
				$weeks = floor($days / 7);
				$days = $days - ($weeks * 7);
			}
		} else {
			$years = $months = $weeks = 0;
			$days = floor($diff / 86400);

			$diff = $diff - ($days * 86400);

			$hours = floor($diff / 3600);
			$diff = $diff - ($hours * 3600);

			$minutes = floor($diff / 60);
			$diff = $diff - ($minutes * 60);
			$seconds = $diff;
		}
		$relativeDate = '';
		$diff = $futureTime - $pastTime;

		if ($diff > abs($now - strtotime($end))) {
			$relativeDate = sprintf('%s', date($format, $inSeconds));
		} else {
			if ($years > 0) {
				// years and months and days
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t('time', '{n} year|{n} years|{n} years|{n} years', $years);
				$relativeDate .= $months > 0 ? ($relativeDate ? ', ' : '') .  Yii::t('time', '{n} month|{n} months|{n} months|{n} months', $months) : '';
				//$relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} неделю|{n} недели|{n} недель|{n} недели', $weeks) : '';
				//$relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} день|{n} дня|{n} дней|{n} дня', $days) : '';
			} elseif (abs($months) > 0) {
				// months, weeks and days
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t('time', '{n} month|{n} months|{n} months|{n} months', $months);
				//$relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} неделю|{n} недели|{n} недель|{n} недели', $weeks) : '';
				//$relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} день|{n} дня|{n} дней|{n} дня', $days) : '';
			} elseif (abs($weeks) > 0) {
				// weeks and days
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t('time', '{n} week|{n} weeks|{n} weeks|{n} weeks', $weeks);
				//$relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} день|{n} дня|{n} дней|{n} дня', $days) : '';
			} elseif (abs($days) > 0) {
				// days and hours
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t('time', '{n} day|{n} days|{n} days|{n} days', $days);
				//$relativeDate .= $hours > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} час|{n} часа|{n} часов|{n} часа', $hours) : '';
			} elseif (abs($hours) > 0) {
				// hours and minutes
                              //  if(abs($hours == 1)){
                               //     $relativeDate .= ($relativeDate ? ', ' : '') . Yii::t('time', 'about an hour|{n} hours|{n} hours|{n} hours', $hours);
                              //  } else {
                                    $relativeDate .= ($relativeDate ? ', ' : '') . Yii::t('time', '{n} hour|{n} hours|{n} hours|{n} hours', $hours);
                               // }
				
                                //$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t(null, '{n} час|{n} часа|{n} часов|{n} часа', $hours);
				$relativeDate .= $minutes > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} минута|{n} минуты|{n} минут|{n} минуты', $minutes) : '';
			} elseif (abs($minutes) > 0) {
				// minutes only
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t('time','{n} minute|{n} minutes|{n} minutes|{n} minutes', $minutes);
			} else {
				// seconds only
				//$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t(null, '{n} секунду|{n} секунды|{n} секунд|{n} секунды', $seconds);
                               $relativeDate .= ($relativeDate ? ', ' : '') . Yii::t('time','less than a minute', $seconds);
			}

			if (!$backwards) {
				if(!$nowt)
				$relativeDate = sprintf('%s '.Yii::t('time','ago'), $relativeDate);
				else
				$relativeDate = sprintf('%s ', $relativeDate);	
			}
		}
		return $relativeDate;
	}


	/**
	 * Возвращает количество дней в месяце
	 * 
	 * @param integer $month
	 * @param integer $year
	 * @return string
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function daysInMonth($month=null, $year=null)
	{
		if (!is_numeric($month) || $month<1 || $month>12)
			$month = date('m');
		
		if (!is_numeric($year) || $year<0)
			$year = date('Y');
		
		return date('t', mktime(0,0,0,$month,1,$year));
	}
        /**
	 * Переводим TIMESTAMP в формат вида: 5 дн. назад
	 * или 1 мин. назад и тп.
	 *
	 * @param unknown_type $date_time
	 * @return unknown
	 */
	public static function getTimeAgo($date_time)
	{
		$timeAgo = time() - strtotime($date_time);
		$timePer = array(
			'day' 	=> array(3600 * 24, 'дн.'),
			'hour' 	=> array(3600, ''),
			'min' 	=> array(60, 'мин.'),
			'sek' 	=> array(1, 'сек.'),
			);
		foreach ($timePer as $type =>  $tp) {
			$tpn = floor($timeAgo / $tp[0]);
			if ($tpn){
				
				switch ($type) {
					case 'hour':
						if (in_array($tpn, array(1, 21))){
							$tp[1] = 'час';
						}elseif (in_array($tpn, array(2, 3, 4, 22, 23)) ) {
							$tp[1] = 'часa';
						}else {
							$tp[1] = 'часов';
						}
						break;
				}
				return $tpn.' '.$tp[1].' назад';
			}
		}
	}
        public function Rusdate($date)
        {
            
                $year = substr($date, 0, 4);
                $month = (int) substr($date, 5, 2);
                $day = (int) substr($date, 8, 2);
                $month_ru = array("", "января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
                return ($day . ' ' . $month_ru[(int)$month] . ' ' . $year);
   
            
        }
         public function Rusdatewoman($date)
        {
            
                $year = substr($date, 0, 4);
                $month = (int) substr($date, 5, 2);
                $day = (int) substr($date, 8, 2);
                $month_ru = array("", "января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
                return ($day . ' ' . $month_ru[(int)$month]);
   
            
        }

        // Converts a unix timestamp to an ics-friendly format
		// NOTE: "Z" means that this timestamp is a UTC timestamp. If you need
		// to set a locale, remove the "\Z" and modify DTEND, DTSTAMP and DTSTART
		// with TZID properties (see RFC 5545 section 3.3.5 for info)
		//
		// Also note that we are using "H" instead of "g" because iCalendar's Time format
		// requires 24-hour time (see RFC 5545 section 3.3.12 for info).
		public function dateToCal($timestamp) {
		  return date('Ymd\THis\Z', $timestamp);
		}

}
