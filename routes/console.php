<?php

use App\Actions\Console\ExpireStrandedTickets;
use App\Actions\Web3\UpdatePaymentStatusFromContractEvents;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('payments:update-status-from-contract-events', function (UpdatePaymentStatusFromContractEvents $updateAction){
    $result = $updateAction->update(\App\Enums\AvailableCurrenciesEnum::POL);
    echo json_encode($result);

//    foreach (\App\Enums\AvailableCurrenciesEnum::cases() as $currency){
//        $result = $updateAction->update($currency);
//        echo $result;
//    }
})->everyMinute();

Artisan::command('tickets:expire-stranded', fn (ExpireStrandedTickets $action) => $action->expire() )->hourly();
