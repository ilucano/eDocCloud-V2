<?php

class Helpers
{
    public static function money($amount, $symbol = '$', $locale = 'en_US')
    {
        setlocale(LC_MONETARY, $locale);

        return $symbol . money_format('%i', $amount);
    }

    /**
     *  Convert MYSQL date time to US date time.
     *
     *  @param string $date MYSQL FORMAT DATE YYYY-MM-DD hh:mm:ss
     *
     *  @return date format F j, Y, g:i a if valid date input
     */
    public static function niceDateTime($date)
    {
        if ($date <= '1970-01-01 00:00:01') {
            return '';
        }

        return date('F j, Y, g:i a', strtotime($date));
    }

    public static function getLastQuery()
    {
        $queries = DB::getQueryLog();
        $last_query = end($queries);

        return $last_query;
    }

    public static function bytesToMegabytes($bytes, $postfix = 'MB')
    {
        return number_format($bytes / 1024 / 1024, 2).$postfix;
    }

    public static function bytesToGigabytes($bytes, $postfix = 'GB')
    {
        return number_format($bytes / 1024 / 1024 / 1024, 2).$postfix;
    }

    /**
     * Return string contains X days Y minutes.
     *
     * @param int $tm Timestamp unix
     */
    public static function timeAgo($tm, $rcs = 0)
    {
        $cur_tm = time();
        $dif = $cur_tm-$tm;
        $pds = array('second','minute','hour','day','week','month','year','decade');
        $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
        for ($v = sizeof($lngh)-1; ($v >= 0) && (($no = $dif/$lngh[$v]) <= 1); $v--);
        if ($v < 0) {
            $v = 0;
        }
        $_tm = $cur_tm-($dif%$lngh[$v]);

        $no = floor($no);
        if ($no != 1) {
            $pds[$v] .= 's';
        }
        $x = sprintf('%d %s ', $no, $pds[$v]);
        if (($rcs == 1) && ($v >= 1) && (($cur_tm-$_tm) > 0)) {
            $x .= $this->timeAgo($_tm);
        }

        return $x;
    }

    public static function renderMetaAttributeFormFilter($type, $attributeId, $label, $attributeOption = null)
    {
        $html = '';

        switch ($type) {
            case 'string':
                $html .= '<label for="'.$attributeId.'">'.$label.'</label>';
                $html .= Form::text($attributeId, '', array('class' => 'form-control'));
                break;

            case 'boolean':

                $html .= '<label for="'.$attributeId.'">'.$label.'</label>';
                $html .= '<div class="radio"><label class="radio-inline">';
                $html .= Form::radio($attributeId, 1, false).' Yes ';
                $html .= '</label>';
                $html .= '<label class="radio-inline">';
                $html .= Form::radio($attributeId, 0, false).' No ';
                $html .= '</label></div>';
                break;

        }

        $html =  '<div class="form-group">'.$html.'</div>';

        return $html;
    }
}
