<?php

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
                'path' => $date->format('Y-m-d'),
                'day' => $date->day,
                'withinMonth' => $date->between($startOfMonth, $endOfMonth),
            ])
            ->chunk(7);
});

?>

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
                        <a
                            href="{{ $day['path'] }}"
                            @class(['bg-gray-200' => !$day['withinMonth'], "block p-2 hover:bg-gray-300"])
                        >
                            {{ $day['day'] }}
                        </a>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
