<?php

use App\Models\Day;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

use function Livewire\Volt\{mount, state};

state([
    "weeks",
    "daysOfWeek" => ["Pn", "Wt", "Śr", "Cz", "Pt", "Sb", "Nd"]
]);

mount(function () {
    $now = Carbon::now();
    $year = $now->year;
    $month = $now->month;
    $startOfMonth = CarbonImmutable::create($year, $month);
    $endOfMonth = $startOfMonth->endOfMonth();
    $startOfWeek = $startOfMonth->startOfWeek(Carbon::MONDAY);
    $endOfWeek = $endOfMonth->endOfWeek(Carbon::SUNDAY);

    $this->weeks =collect($startOfWeek->toPeriod($endOfWeek)->toArray())
            ->map(fn ($date) => [
                'date' => $date->format('Y-m-d'),
                'day' => $date->day,
                'withinMonth' => $date->between($startOfMonth, $endOfMonth),
            ])
            ->chunk(7);
});

$navigateToDay = function (string $date) {
    $day = Auth::user()->days()->firstOrCreate(['date' => $date]);

    $this->redirect(route("days.show", $day), navigate: true);
}

// https://tighten.com/insights/building-a-calendar-with-carbon/
?>

<div class="bg-white p-4">
<table class="m-auto text-center">
    <thead>
        <tr>
            @foreach ($daysOfWeek as $day)
                <td>{{ $day }}</td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($weeks as $days)
            <tr>
                @foreach ($days as $day)
                    <td>
                        <button
                            @class(['bg-gray-200' => !$day['withinMonth'], "p-2 hover:bg-gray-300 block w-full"])
                            wire:click="navigateToDay('{{ $day['date'] }}')"
                        >
                            {{ $day['day'] }}
                        </button>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
</div>
