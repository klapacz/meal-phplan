<?php

use function Livewire\Volt\{state};

$getTransactions = fn() => ($this->transactions = Auth::user()->transactions()->get());

state(['transactions' => $getTransactions]);

?>

<table class="min-w-full divide-y divide-gray-200 ">
    <thead class="bg-gray-50 ">
        <tr>
            <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 ">
                <span>Amount</span>
            </th>

            <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
                Status
            </th>

            <th scope="col" class="relative py-3.5 px-4">
                <span class="sr-only">Edit</span>
            </th>
        </tr>
    </thead>

    <tbody class="bg-white divide-y divide-gray-200 ">
        @foreach ($transactions as $transaction)
            <tr>
                <td class="px-4 py-4 font-medium whitespace-nowrap">
                    <div>
                        <h2 class="font-medium text-gray-800">{{ abs($transaction->amount) }}</h2>
                    </div>
                </td>
                <td class="px-12 py-4 font-medium whitespace-nowrap">
                    @if ($transaction->amount > 0)
                        <div
                            class="inline-flex items-center gap-x-1.5 rounded-md px-1.5 py-0.5 text-sm/5 font-medium sm:text-xs/5 forced-colors:outline bg-green-400/20 text-green-700 group-data-[hover]:bg-green-400/30">
                            Income
                        </div>
                    @else
                        <div
                            class="inline-flex items-center gap-x-1.5 rounded-md px-1.5 py-0.5 text-sm/5 font-medium sm:text-xs/5 forced-colors:outline bg-rose-400/15 text-rose-700 group-data-[hover]:bg-rose-400/25 ">
                            Outcome
                        </div>
                    @endif
                </td>

                <td class="px-4 py-4 text-sm whitespace-nowrap">
                <div class="flex justify-end">

                    <button class="px-1 py-1 text-gray-500 transition-colors duration-200 rounded-lg hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                        </svg>
                    </button>
                </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
