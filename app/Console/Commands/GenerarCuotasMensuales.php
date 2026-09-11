<?php

namespace App\Console\Commands;

use App\Services\CuotaGenerator;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('cuotas:generar {--year=} {--month=}')]
#[Description('Genera las cuotas mensuales de los contratos activos para el periodo indicado (por defecto el mes actual).')]
class GenerarCuotasMensuales extends Command
{
    public function handle(CuotaGenerator $generator): int
    {
        $year = $this->option('year') ? (int) $this->option('year') : null;
        $month = $this->option('month') ? (int) $this->option('month') : null;

        $generadas = $generator->generarParaPeriodo($year, $month);

        $this->info("Cuotas generadas: {$generadas}");

        return self::SUCCESS;
    }
}
