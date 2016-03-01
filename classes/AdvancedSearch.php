<?php
class AdvancedSearch {
    public static function lookFor($serchQuery, $infoStack, $unikIDkey, $ignoreFields = array())
    {
        $oderArr = array();
        $stackLength = count($infoStack) - 1;
        if(!$stackLength) {
            return '<p>Error, no stack provided</p>';
        }
        if(ctype_space($serchQuery))
        {
            return $infoStack;
        }
        $x = 0;
        $serchArr = preg_split("/[\s,]+/", $serchQuery);
        foreach($serchArr as $serchVal) {
            foreach ($infoStack as $key => $array) {
                if(!array_key_exists($key, $oderArr))
                {
                    $oderArr[$key] = 0;
                    $x = 0;
                }
                foreach ($array as $nameKey => $val) {
                    /*echo 'match: ' . strripos($val,$serchVal) . '<br>';
                    echo '$val: ' . $val . '<br>';
                    echo '$serchVal: ' . $serchVal . '<br>';
                    if($nameKey != $unikIDkey) {
                        echo '$nameKey: ' . $nameKey . '<br>';
                    }
                    echo '<hr><br>'; */
                    if(strripos(strtolower($val),strtolower($serchVal)) !== false && $nameKey != $unikIDkey) {
                        $x++;
                        $oderArr[$key] = $x;
                    }
                }
            }
        }
        arsort($oderArr);
        $result = array();
        foreach($oderArr as $key => $val)
        {
            $result[] = $infoStack[$key];
        }
        /*echo '<pre class="columns twelve">';
        print_r($oderArr);
        print_r($serchArr);
        print_r($infoStack);
        echo '<pre>';*/
        return $result;
    }
}