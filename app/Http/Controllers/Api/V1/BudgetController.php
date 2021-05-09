<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BudgetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    protected $budgetService;

    public function __construct(BudgetService $budgetService)
    {
        $this->budgetService = $budgetService;
    }

    public function getBudget(Request $request): JsonResponse
    {
        $user = User::find(1);

        $budget = $user->budget;

        return response()->json(
            [
                'budget' => $budget->amount
            ],
            JsonResponse::HTTP_OK
        );
    }

    public function expense(Request $request): JsonResponse
    {
        $data = $request->validated();

        $data['user_id'] = 1;

        $this->budgetService->expense($data);

        return response()->json(
            [],
            JsonResponse::HTTP_CREATED
        );
    }
}
