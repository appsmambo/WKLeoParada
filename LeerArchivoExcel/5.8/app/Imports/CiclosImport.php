<?php

namespace App;
use Maatwebsite\Excel\Concerns\ToModel;

class CiclosImport implements ToModel
{
  public function model(array $row)
  {
    return new Ciclos(
      [
        'cic_neqdbc' => $row[4],
        'cic_tm'     => $row[5],
        'cic_tej'    => $row[6]
      ]
    );
  }
}
