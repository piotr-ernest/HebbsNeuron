<?php

/**
 * Description of ArtificialNeuron
 *
 * @author rnest
 */
abstract class ArtificialNeuron
{
    /*
     * dane wejściowe
     */

    protected $input;

    /*
     * dane wyjściowe
     */
    protected $output;

    /*
     * tablica wag
     */
    protected $scales;

    /*
     * stała uczenia
     */
    protected $constantLearning = 1;

    /*
     * wartość progowa
     */
    protected $thresholdValue = false;
    protected $functionType;

    /*
     * konstruktor klasy
     */
    protected static $summarize = array();

    public function __construct()
    {
        
    }

    /*
     * metoda której implementacja w klasach
     * rozszerzających moze ułatwić
     * tworzenie nowych neuronów
     */

    public function __clone()
    {
        throw new Exception('Clone method not supported yet.');
    }

    /*
     * metoda pobierająca dane
     * wejściowe
     */

    public function setInput(Array $input)
    {
        $this->input = $input;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function setConstantLearning($cl)
    {
        $this->constantLearning = $cl;
    }

    public function getConstantLearning()
    {
        return $this->constantLearning;
    }

    public function setTresholdValue($tv)
    {
        $this->thresholdValue = $tv;
    }

    public function getTresholdValue()
    {
        return $this->thresholdValue;
    }

    public function getOutput()
    {
        return $this->output;
    }

    protected function setOutput($output)
    {
        $this->output = $output;
    }

    /*
     * metoda ustawiająca tablicę wag
     */

    public function setScales(Array $scales)
    {
        $this->scales = $scales;
    }

    /*
     * metoda zwracająca tablicę wag
     */

    public function getScales()
    {
        return $this->scales;
    }

    public function setFunctionType($type)
    {
        $this->functionType = $type;
    }

    public function getFunctionType()
    {
        return $this->functionType;
    }

    /*
     * blok sumujący
     * mnoży odpowiednie elementy
     * tablicy danych wejściowych i tablicy wag
     * i zwraca sumę wartości elementów tak powstałej tablicy
     */

    protected function aggregateBox($input, $scales)
    {

        $results = array();

        for ($i = 0; $i < count($input); $i++) {
            $results[] = ($input[$i] * $scales[$i]);
        }

        /*
         * jeśli jest ustawiona wartość progowa
         */
        if ($this->thresholdValue) {
            //desc($this->thresholdValue, 0, 'tresh');
            return (array_sum($results) - $this->thresholdValue);
        }

        /*
         * bez wartości progowej
         */
        return array_sum($results);
    }

    /*
     * metoda, w której jest przeprowadzany
     * właściwy proces uczenia neuronu
     */

    public function doLearning()
    {
        $x = $this->getInput();
        $output = array();
        $counter = 0;

        while ($input = each($x)) {

            $counter++;

            $sum = $this->aggregateBox($input['value'], $this->getScales());
            $function = $this->resolveFunctionType();
            $y = $this->activationFunction($function, $sum);
            $this->setOutput($y);
            $output[] = $this->getOutput();

            self::summarize($counter, 'W' . $counter, $this->getScales());
            self::summarize($counter, 'NET' . $counter, $sum);
            self::summarize($counter, 'Y' . $counter, $this->getOutput());

            $this->upgradeScales($input['value']);
        }

        return $output;
    }

    protected static function summarize($counter, $index, $value)
    {
        self::$summarize[$counter][$index] = $value;
    }

    public static function getSummarize()
    {
        return self::$summarize;
    }

    protected function resolveFunctionType()
    {
        $functionType = $this->getFunctionType();

        switch ($functionType) {
            case 'signum':
                return function($x) {
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
                };

                break;
            case 'sigmoidal' :

                return function($x) {
                    if (!is_numeric($x)) {
                        throw new Exception('Podany argument musi być liczbą.');
                    }
                    return ((2 / (1 + exp(-$x))) - 1);
                };

                break;
            default:
                throw new Exception('Niewłaściwa funkcja aktywacji.');
                break;
        }
    }

    /*
     * metoda do implementacji właściwej funkcji aktywacji
     * w klasie dziedziczącej
     */

    protected abstract function activationFunction(callable $funct, $arg);

    /*
     * metoda abstrakcyjna modyfikująca wagi
     * dla kolejnych iteracji,
     * do implementacji w klasie potomnej
     */

    protected abstract function upgradeScales(Array $input);
}

function signum($x)
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
