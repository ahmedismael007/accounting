<?php

namespace App\Http\Controllers\Tenant\Accounting\Accountant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Accountants\CreateTaxRateRequest;
use App\Http\Requests\Tenant\Accounting\Accountants\UpdateTaxRateRequest;
use App\Services\V1\Accounting\TaxRateService;
use Illuminate\Http\Request;
use function dd;

class TaxRateController extends Controller
{
    public function __construct(protected TaxRateService $taxRateService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->taxRateService->index($request);

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaxRateRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->taxRateService->store($validated);
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
        $data = $this->taxRateService->show($id);

        return response()->json(['data' => $data], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaxRateRequest $request, string $id)
    {
        $validated = $request->validated();
        try {
            $this->taxRateService->update($validated, $id);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => trans('crud.updated')], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        try {
            $this->taxRateService->destroy($ids);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => trans('crud.deleted')], 200);
    }
}
