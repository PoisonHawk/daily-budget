<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Operation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BudgetService
{
    public function addBudget($userId, $amount)
    {
        $income = Operation::where([
            'user_id' => $userId,
            'type' => Operation::TYPE_INCOME
        ])
        ->whereDate('created_at', '=', Carbon::today()->toDateString())
        ->first();

        if ($income) {
            return;
        }

        DB::beginTransaction();

        try {
            Operation::create([
                'user_id' => $userId,
                'amount' => $amount,
                'type' => Operation::TYPE_INCOME
            ]);

            $budget = Budget::where('user_id', $userId)->first();

            $budget->amount = $budget->amount + $amount;
            $budget->save();
        } catch (\Exception $e) {
            \Log::error($e);

            DB::rollBack();
        }

        DB::commit();
    }

    public function expense($userId, $amount)
    {
        $budget = Budget::where('user_id', $userId)->first();

        $budget->amount = $budget->amount - $amount;
        $budget->save();
    }

    public function income($userId, $amount)
    {
        $budget = Budget::where('user_id', $userId)->first();

        $budget->amount = $budget->amount + $amount;
        $budget->save();
    }
}
