<?php
class Helpers {
	
    public static function money($amount, $symbol = '$', $locale = 'en_US') {
		setlocale(LC_MONETARY, $locale);
        
		return $symbol . money_format('%i', $amount);
    }
	
	/**
	 *  Convert MYSQL date time to US date time
	 *  @param string $date MYSQL FORMAT DATE YYYY-MM-DD hh:mm:ss
	 *  @return date format F j, Y, g:i a if valid date input
	 */
	public static function niceDateTime($date) {
		
		if($date <= '1970-01-01 00:00:01') {
			return '';
		}
		
		return date("F j, Y, g:i a", strtotime($date));
	}

}