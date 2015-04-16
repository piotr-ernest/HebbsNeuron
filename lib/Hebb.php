<?php

require 'ArtificialNeuron.php';
require 'CoreMath.php';

/**
 * Description of Hebb
 *
 * @author rnest
 */
class Hebb extends ArtificialNeuron
{
    /*
     * w tej metodzie należy zaimplementować
     * właściwą funkcję aktywacji
     * fi = wynik z bloku sumującego
     * (metoda aggregateBox)
     */

    protected function activationFunction(callable $funct, $arg)
    {
        return $funct($arg);
    }

    protected function upgradeScales(Array $input)
    {

        $y = $this->getOutput();
        $cl = $this->getConstantLearning();
        $currentScales = $this->getScales();

        $y_by_input = CoreMath::matrix_MultiplicationByScalar($input, $y);
        $y_by_input_by_cl = CoreMath::matrix_MultiplicationByScalar($y_by_input, $cl);

        $newScales = CoreMath::matrix_AdditionToMatrix($y_by_input_by_cl, $currentScales);
        $this->setScales($newScales);
        
    }

    protected function signum($x)
    {
        if (!is_numeric($x)) {
            throw new Exception('Podany argument musi być liczbą.');
        }

        if ($x < 0) {
            return -1;
        }
        if ($x == 0) {
            return 0;
        }
        if ($x > 0) {
            return 1;
        }
    }

}
