<?php

namespace App\Services;

use App\Models\Operation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OperationService
{
    protected $budgetService;

    public function __construct(BudgetService $budgetService)
    {
        $this->budgetService = $budgetService;
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $operation = Operation::create($data);

            $method = $data['type'] == Operation::TYPE_INCOME ? 'income' : 'expense';

            $this->budgetService->{$method}($data['user_id'], $data['amount']);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e);
        }

        DB::commit();

        return $operation;
    }

    public function delete(int $id)
    {
        $operation = Operation::find($id);

        DB::beginTransaction();

        try {
            $method = $operation->type == Operation::TYPE_INCOME ? 'expense' : 'income';

            $this->budgetService->{$method}($operation->user_id, $operation->amount);

            $operation->delete();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e);
        }

        DB::commit();

        return true;
    }
}
