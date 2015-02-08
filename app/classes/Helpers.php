<?php
class Helpers {
	
    public static function money($amount, $symbol = '$', $locale = 'en_US') {
		setlocale(LC_MONETARY, $locale);
        
		return $symbol . money_format('%i', $amount);
    }
}