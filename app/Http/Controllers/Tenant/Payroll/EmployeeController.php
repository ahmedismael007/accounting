<?php

namespace App\Http\Controllers\Tenant\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Payroll\EmployeeRequest;
use App\Http\Requests\Tenant\Payroll\PayrollRequest;
use App\Models\Tenant\Payroll\Employee;
use App\Services\V1\Payroll\EmployeeService;
use Illuminate\Http\Request;
use Throwable;
use function is_array;
use function response;
use function trans;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->service->index($request);
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        try {
            $this->service->store($request->validated());
            return response()->json(['message' => trans('crud.created')], 201);
        } catch (Throwable $e) {
            return response()->json([
                'message' => trans('crud.create.error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $payroll = $this->service->show($id);
        return response()->json(['data' => $payroll], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, int $id)
    {
        try {
            $this->service->update($id, $request->validated());
            return response()->json(['message' => trans('crud.updated')], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => trans('crud.update.error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => trans('crud.invalid_request')], 422);
        }

        $this->service->destroy($ids);

        return response()->json(['message' => trans('crud.deleted')], 200);
    }
}
