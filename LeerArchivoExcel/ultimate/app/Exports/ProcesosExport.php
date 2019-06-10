<?php

namespace App\Exports;

use App\ProcesoDetalle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProcesosExport implements FromCollection
{
  use Exportable;
  public function collection()
  {
    return ProcesoDetalle::select('igr_area', 'igr_ges', 'igr_trabajador', 'igr_fri', 'igr_ciclott', 'igr_neqdbc_1', 'igr_neqdbc_2', 'igr_neqdbc_3', 'igr_neqdbc_4', 'igr_neqdbc_5', 'igr_peakc_1', 'igr_peakc_2', 'igr_peakc_3', 'igr_peakc_4', 'igr_peakc_5', 'cic_neqdbc_1', 'cic_neqdbc_2', 'cic_neqdbc_3', 'cic_neqdbc_4', 'cic_neqdbc_5', 'cic_tm_1', 'cic_tm_2', 'cic_tm_3', 'cic_tm_4', 'cic_tm_5', 'cic_tej_1', 'cic_tej_2', 'cic_tej_3', 'cic_tej_4', 'cic_tej_5')->orderBy('created_at', 'DESC')->get();
    //return Proceso::all();
  }
}
