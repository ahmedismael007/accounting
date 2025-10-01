<?php

namespace App\Http\Controllers\Tenant\Accounting\Accountants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Accountants\CreateAccountRequest;
use App\Http\Requests\Tenant\Accounting\Accountants\UpdateAccountRequest;
use App\Services\V1\Accounting\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(protected AccountService $accountService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->accountService->index($request);

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAccountRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->accountService->store($validated);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => trans('crud.created')], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->accountService->show($id);

        return response()->json(['data' => $data], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $this->accountService->update($id, $validated);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => 'crud.updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        try {
            $this->accountService->destroy($ids);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => 'crud.deleted'], 200);
    }
}
