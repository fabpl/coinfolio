<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateExchanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:update {exchange}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update exchanges currencies and markets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $exchangeName = $this->argument('exchange');

        $class = 'App\Models\Exchange\\'.ucfirst($exchangeName);
        if (class_exists($class)) {
            $updater = new $class();

            if (!$updater->updateCurrencies()) {
                echo $updater->getApiError();
            }

            if (!$updater->updateMarkets()) {
                echo $updater->getApiError();
            }
        }
    }
}
