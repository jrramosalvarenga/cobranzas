<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Cuota;
use Carbon\Carbon;

class CuotaGenerator
{
    /**
     * Genera las cuotas del periodo indicado (por defecto el mes actual)
     * para todos los contratos activos que aún no tengan cuota de ese periodo.
     *
     * @return int Cantidad de cuotas generadas.
     */
    public function generarParaPeriodo(?int $year = null, ?int $month = null): int
    {
        $year ??= now()->year;
        $month ??= now()->month;

        $generadas = 0;

        Contract::query()
            ->where('status', 'active')
            ->whereDoesntHave('cuotas', function ($query) use ($year, $month) {
                $query->where('period_year', $year)->where('period_month', $month);
            })
            ->with('serviceType')
            ->chunkById(100, function ($contracts) use ($year, $month, &$generadas) {
                foreach ($contracts as $contract) {
                    $dueDate = Carbon::createFromDate($year, $month, 1)
                        ->day(min($contract->billing_day, Carbon::createFromDate($year, $month, 1)->daysInMonth));

                    Cuota::create([
                        'contract_id' => $contract->id,
                        'period_year' => $year,
                        'period_month' => $month,
                        'amount' => $contract->effectiveMonthlyFee(),
                        'due_date' => $dueDate,
                        'status' => 'pendiente',
                    ]);

                    $generadas++;
                }
            });

        return $generadas;
    }
}
