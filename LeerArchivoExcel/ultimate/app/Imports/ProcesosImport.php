<?php

namespace App\Imports;
use App\Proceso;
use Maatwebsite\Excel\Concerns\ToModel;

class ProcesosImport implements ToModel
{
  public function model(array $row)
  {
    return new Proceso(
      [
        'igr_area'       => $row[1],
        'igr_ges'        => $row[2],
        'igr_trabajador' => $row[5],
        'igr_fri'        => $row[12],
        'igr_ciclott'    => $row[19],
        'igr_neqdbc'     => $row[21],
        'igr_peakc_1'    => $row[22]
      ]
    );
  }
}
