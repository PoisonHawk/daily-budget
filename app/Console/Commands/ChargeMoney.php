<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\BudgetService;
use Illuminate\Console\Command;

class ChargeMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'money:charge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chrage money to user';

    protected $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BudgetService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $amount = 1000;

            $this->service->addBudget($user->id, $amount);
        }
    }
}
