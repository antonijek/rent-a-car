<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class Reservations implements FromView
{
  protected $reservations;

  public function __construct($reservations){
      $this->reservations=$reservations;
  }

    public function view():View
    {
return  view('exports.reservations-export',['reservations'=>$this->reservations]);
    }
}
