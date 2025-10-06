<?php

namespace App\Http\Controllers\Tenant\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Payroll\PayrunRequest;
use App\Services\V1\Payroll\PayrunService;
use Illuminate\Http\Request;
use Throwable;

class PayRunController extends Controller
{
    public function __construct(protected PayrunService $service)
    {
    }

    public function index(Request $request)
    {
        $data = $this->service->index($request);
        return response()->json($data, 200);
    }

    public function store(PayrunRequest $request)
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

    public function show(int $id)
    {
        $payroll = $this->service->show($id);
        return response()->json(['data' => $payroll], 200);
    }

    public function update(PayrunRequest $request, int $id)
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
