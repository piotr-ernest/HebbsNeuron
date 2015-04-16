<?php

/**
 * Description of CoreMath
 *
 * @author rnest
 */
class CoreMath
{
    public static function matrix_MultiplicationByScalar(Array $matrix, $scalar)
    {
        if(!is_numeric($scalar)){
            throw new Exception('Zmienna scalar musi być liczbą.');
        }
        $result = array();
        while($elem = each($matrix)){
            $result[] = $elem['value'] * $scalar;
        }
        
        return $result;
    }
    
    public static function matrix_AdditionToMatrix($m1, $m2){
        
        if(count($m1) !== count($m2)){
            throw new Exception('Dodawane macierze muszą być tego samego rozmiaru.');
        }
        $result = array();
        
        for($i = 0; $i < count($m1); $i++){
            $result[] = $m1[$i] + $m2[$i];
        }
        
        return $result;
        
    }
    
}
