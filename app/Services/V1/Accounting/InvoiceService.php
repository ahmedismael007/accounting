<?php

namespace App\Services\V1\Accounting;

use App\Http\Requests\Tenant\Accounting\Sales\InvoiceRequest;
use App\Repositories\V1\Accounting\InvoiceRepo;
use App\Services\V1\Common\QueryBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceService
{

    public function __construct(protected InvoiceRepo $repo, protected QueryBuilderService $queryBuilder, protected CalculatorService $calculator, protected JournalService $journalService)
    {
    }

    public function index(Request $request)
    {
        return $this->queryBuilder->applyQuery($request, $this->repo->query());
    }

    public function store(InvoiceRequest $request): array
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();

            // === Calculate totals ===
            $totals = $this->calculator->calculateTotals($data);

            // === Create the invoice ===
            $invoice = $this->repo->create(array_merge($data, $totals));

            // === Attach polymorphic relations ===
            $invoice->lineItems()->createMany($data['line_items']);

            if (!empty($data['discount'])) {
                $invoice->discount()->create($data['discount']);
            }

            if (!empty($data['retention'])) {
                $invoice->retention()->create($data['retention']);
            }

            $this->journalService->createFromInvoice($invoice);

            return [
                'message' => trans('crud.created'),
                'data' => $invoice->load(['lineItems', 'discount', 'retention']),
            ];
        });
    }

    public function show(string $id)
    {
        $invoice = $this->repo->findOrFail($id);

        return [
            'data' => $invoice,
        ];
    }

    public function update(InvoiceRequest $request, string $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $invoice = $this->repo->update($id, $request->validated());

            return [
                'message' => trans('crud.updated'),
                'data' => $invoice,
            ];
        });
    }

    public function destroy(array $ids)
    {
        $this->repo->destroy($ids);

        return [
            'message' => trans('crud.deleted'),
        ];
    }
}
