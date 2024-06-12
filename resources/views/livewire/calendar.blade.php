<?php

use Carbon\Carbon;
use Carbon\CarbonImmutable;

use function Livewire\Volt\{computed, on, placeholder, state};

state([
    'daysOfWeek' => ['Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'Sb', 'Nd'],
])->locked();

state([
    'month' => Carbon::now()->month,
    'year' => Carbon::now()->year,
])->url();

$weeks = computed(function () {
    $start = microtime(true);
    $startOfMonth = CarbonImmutable::create($this->year, $this->month);
    $endOfMonth = $startOfMonth->endOfMonth();
    $startOfWeek = $startOfMonth->startOfWeek(Carbon::MONDAY);
    $endOfWeek = $endOfMonth->endOfWeek(Carbon::SUNDAY);

    $days = Auth::user()->days()->whereDate('date', '>=', $startOfWeek)->whereDate('date', '<=', $endOfWeek)->withCount('dishes')->get();

    function getColor($dishes_count)
    {
        if ($dishes_count === 5) {
            return 'bg-green-200 hover:bg-green-500';
        }

        if ($dishes_count > 0) {
            return 'bg-blue-200 hover:bg-blue-500';
        }
        return 'hover:bg-gray-300 ';
    }

    $result = collect($startOfWeek->toPeriod($endOfWeek)->toArray())
        ->map(function ($date) use ($startOfMonth, $endOfMonth, $days) {
            $day = $days->where('date', $date)->first();
            return [
                'date' => $date->format('Y-m-d'),
                'day' => $date->day,
                'withinMonth' => $date->between($startOfMonth, $endOfMonth),
                'classes' => getColor($day ? $day->dishes_count : 0),
            ];
        })
        ->chunk(7);

    $diff = microtime(true) - $start;
    Log::info("Generating calendar took {$diff} seconds");

    return $result;
});

$monthInstance = computed(function () {
    return CarbonImmutable::create($this->year, $this->month);
});

$navigateToNextMonth = function () {
    $nextMonth = $this->monthInstance->addMonth();

    $this->month = $nextMonth->month;
    $this->year = $nextMonth->year;
    unset($this->monthInstance);
};

$navigateToPreviousMonth = function () {
    $previousMonth = $this->monthInstance->subMonth();

    $this->month = $previousMonth->month;
    $this->year = $previousMonth->year;
    unset($this->monthInstance);
};

$navigateToDay = function (string $date) {
    $day = Auth::user()
        ->days()
        ->firstOrCreate(['date' => $date]);

    $this->redirect(route('days.show', $day), navigate: true);
};

// https://tighten.com/insights/building-a-calendar-with-carbon/

on([
    'day-dish-updated' => function ($day) {
        unset($this->weeks);
    },
]);

?>

<div class="bg-white p-4 max-w-min grid gap-4 relative">
    <div wire:loading.delay>
        <div class="absolute top-0 left-0 w-full h-full bg-white/80 flex justify-center items-center">
            <x-lucide-loader-circle class="w-12 h-12 animate-spin" />
        </div>
    </div>
    <div class="flex">
        <button wire:click="navigateToPreviousMonth">
            <x-lucide-chevron-left class="w-6 h-6" />
        </button>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight grow text-center">
            {{ ucfirst($this->monthInstance->translatedFormat('F Y')) }}
        </h2>
        <button wire:click="navigateToNextMonth">
            <x-lucide-chevron-right class="w-6 h-6" />
        </button>
    </div>
    <table class="m-auto text-center">
        <thead>
            <tr>
                @foreach ($daysOfWeek as $day)
                    <td>{{ $day }}</td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($this->weeks as $days)
                <tr>
                    @foreach ($days as $day)
                        <td>
                            <button @class([
                                'bg-gray-200' => !$day['withinMonth'],
                                'p-2 block w-full rounded',
                                $day['classes'],
                            ]) wire:click="navigateToDay('{{ $day['date'] }}')">
                                {{ $day['day'] }}
                            </button>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
