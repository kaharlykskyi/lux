<?php
/**
 * Created by PhpStorm.
 * User: Seliv
 * Date: 19.12.2018
 * Time: 16:48
 */

namespace App\TecDoc;


use Sunrise\Vin\Vin;
use const App\TecDoc\YEARS;

class VinDecoder extends Vin
{
    protected $year;

    public function __construct(string $value)
    {
        parent::__construct($value);



    }

}