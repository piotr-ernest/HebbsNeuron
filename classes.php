<?php


require 'lib/Hebb.php';

$totals = null;
$error_numbers = false;

if (isset($_POST['number_indexes']) && isset($_POST['number_x'])) {
    
    $number_indexes = $_POST['number_indexes'];
    $number_x = $_POST['number_x'];
    
}

if (isset($_POST['vectors'])) {

    unset($_POST['vectors']);
    $const_learn = $_POST['const_learn'];
    unset($_POST['const_learn']);
    $count = $_POST['count'];
    unset($_POST['count']);
    $x0 = $_POST['x0'];
    unset($_POST['x0']);
    $function = $_POST['function'];
    unset($_POST['function']);

    $rowData = array_map(function($elem){
        $res = htmlspecialchars(trim($elem));
        return (float) str_replace(',', '.', $res);
    }, $_POST);
    
    $arrays = array_chunk($rowData, $count, true);
    
    $hebb = new Hebb();
    $hebb->setFunctionType($function);
    
    if (!empty($const_learn)) {
        $hebb->setConstantLearning($const_learn);
    }

    if (!empty($x0)) {
        $hebb->setTresholdValue($x0);
    }

    $hebb->setScales(array_values($arrays[0]));

    unset($arrays[0]);

    $inputArray = array();

    foreach($arrays as $key => $val){
        while($v = each($val)){
            $inputArray[$key][] = $v['value'];
        }
    }

    $hebb->setInput(array_values($inputArray));
    $learningResult = $hebb->doLearning();
    
    $rowTotals = Hebb::getSummarize();
    
    $totals = '';
    
    while($row = each($rowTotals)){
        $totals .= 'Wartości wektora wag W' . $row['key'] . ': ' . implode(', ', $row['value']['W'. $row['key']]) . '</br>';
        $totals .= 'Wartość NET' . $row['key'] . ': ' . $row['value']['NET'. $row['key']] . '</br>';
        $totals .= 'Wartość na wyjściu Y' . $row['key'] . ': ' . $row['value']['Y'. $row['key']] . '</br><hr>';
    }
    
}

function desc($data, $exit = false, $title = '', $out = false)
{

    if ($out) {
        return print_r($data, $out);
    }

    echo '<div style="background: #ff6600; color: #003333; padding: 10px; overflow-x: auto; z-index: 9999;'
    . 'border-radius: 10px; margin: 10px;">';
    echo '<h4>' . $title . '</h4>';
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    echo '</div>';

    if ($exit) {
        exit;
    }
}
