<?php

namespace App\Services\V1\Accounting;

use App\Models\Tenant\Accounting\Accountants\Journal;
use App\Models\Tenant\Accounting\Sales\Invoice;
use App\Repositories\V1\Accounting\JournalRepo;
use App\Services\V1\Common\PdfService;
use App\Services\V1\Common\QueryBuilderService;
use App\Services\V1\Common\ReferenceValueFormatterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\Tenant\Inventory\InventoryAdjustment;
use function abs;
use function collect;
use function is_array;
use function now;
use function response;
use function tenant;
use function trans;
use function trim;

class JournalService
{
    public function __construct(
        protected JournalRepo                    $repo,
        protected PdfService                     $pdfService,
        protected ReferenceValueFormatterService $formatter,
        protected QueryBuilderService            $queryBuilder,
    )
    {
    }

    public function index(Request $request)
    {
        $query = Journal::where('type', 'manual')->with('journal_line_items');

        $data = $this->queryBuilder->applyQuery($request, $query);

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $user_with_email = $this->formatUserWithEmail(Auth::user()) ?? 'SYSTEM';

        DB::beginTransaction();
        try {
            $journal = $this->repo->create([
                'date' => $request->date,
                'reference' => $request->reference,
                'notes' => $request->notes,
            ]);

            $lineItems = collect($request->input('journal_line_items'))->map(function ($item) use ($user_with_email, $journal) {
                return [
                    'created_by' => $user_with_email ?? 'SYSTEM',
                    'account_id' => $item['account_id'],
                    'description' => $item['description'] ?? null,
                    'currency' => $item['currency'],
                    'exchange_rate' => $item['exchange_rate'] ?? 1,
                    'debit' => $item['debit'] ?? null,
                    'credit' => $item['credit'] ?? null,
                    'debit_dc' => $item['debit_dc'],
                    'credit_dc' => $item['credit_dc'],
                    'tax_rate_id' => $item['tax_rate'] ?? null,
                    'contact_id' => $item['contact_id'] ?? null,
                    'project_id' => $item['project_id'] ?? null,
                    'branch_id' => $item['branch_id'] ?? null,
                    'cost_center_id' => $item['cost_center_id'] ?? null,
                    'source_type' => 'manual_journal',
                    'source_id' => $journal->id,
                ];
            });

            $totalDebit = $lineItems->sum('debit');
            $totalCredit = $lineItems->sum('credit');

            if ($totalDebit !== $totalCredit) {
                return response()->json(['message' => trans('accounting.debit_credit_must_equal')], 422);
            }

            $journal->journal_line_items()->createMany($lineItems);

            $company = tenant();
            $formatted = $this->formatter->format($lineItems);
            $total_debit = $lineItems->sum('debit_dc');
            $total_credit = $lineItems->sum('credit_dc');

            $this->pdfService->generate_PDF(
                'pdf.journal',
                [
                    'company' => $company,
                    'notes' => $request->notes,
                    'line_items' => $formatted,
                    'date' => $request->date,
                    'id' => $journal->id,
                    'total_debit' => $total_debit,
                    'total_credit' => $total_credit,
                ],
                'Manual_Journal',
                $journal->id,
                $journal,
                'manual_journal'
            );

            DB::commit();

            return response()->json(['message' => trans('crud.created')], 201);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => trans('crud.create.error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        $journal = $this->repo->findWithRelations($id, ['journal_line_items', 'media']);
        return response()->json(['data' => $journal], 200);
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $journal = $this->repo->findOrFail($id);

            $journal->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'notes' => $request->notes,
            ]);

            $journal->journal_line_items()->delete();

            $user_with_email = $this->formatUserWithEmail(Auth::user());
            $lineItems = collect($request->input('journal_line_items'))->map(function ($item) use ($user_with_email, $journal) {
                return [
                    'created_by' => $user_with_email ?? 'SYSTEM',
                    'account_id' => $item['account_id'],
                    'description' => $item['description'] ?? null,
                    'currency' => $item['currency'],
                    'exchange_rate' => $item['exchange_rate'] ?? 1,
                    'debit' => $item['debit'] ?? null,
                    'credit' => $item['credit'] ?? null,
                    'debit_dc' => $item['debit_dc'],
                    'credit_dc' => $item['credit_dc'],
                    'tax_rate_id' => $item['tax_rate'] ?? null,
                    'contact_id' => $item['contact_id'] ?? null,
                    'project_id' => $item['project_id'] ?? null,
                    'branch_id' => $item['branch_id'] ?? null,
                    'cost_center_id' => $item['cost_center_id'] ?? null,
                    'source_type' => 'manual_journal',
                    'source_id' => $journal->id,
                ];
            });

            $totalDebit = $lineItems->sum('debit');
            $totalCredit = $lineItems->sum('credit');

            if ($totalDebit !== $totalCredit) {
                return response()->json(['message' => trans('accounting.debit_credit_must_equal')], 422);
            }

            $journal->journal_line_items()->createMany($lineItems);

            DB::commit();
            return response()->json(['message' => trans('crud.updated')], 200);
        } catch (Throwable $e) {
            DB::rollBack();
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
            return response()->json(['error' => trans('crud.delete.error')], 422);
        }

        $journals = $this->repo->findWhereIn('id', $ids);

        foreach ($journals as $journal) {
            if ($journal->type === 'auto') {
                return response()->json(['message' => trans('accounting.cannot_delete_auto_journal')], 422);
            }
        }

        $this->repo->deleteMany($ids);

        return response()->json(['message' => trans('crud.deleted')], 200);
    }

    private function formatUserWithEmail($user): string
    {
        $user_with_email = 'SYSTEM';

        if ($user) {
            $firstName = $user['first_name'] ?? ($user->first_name ?? '');
            $lastName = $user['last_name'] ?? ($user->last_name ?? '');
            $email = $user['email'] ?? ($user->email ?? '');
            $name = trim("{$firstName} {$lastName}");
            $user_with_email = $name ? "{$name} - {$email}" : ($email ?: 'UNKNOWN USER');
        }

        return $user_with_email;
    }

    public function createFromAdjustment(InventoryAdjustment $adjustment): Journal
    {
        $amount = $adjustment->total_adjustment_amount;
        $cogsAccountID = 53;

        $journal = $adjustment->journals()->create([
            'reference' => 'auto',
            'date' => now(),
            'notes' => $adjustment->notes ?? null,
            'type' => 'auto',
        ]);

        $currency = $adjustment->currency ?? ($adjustment->getAttribute('currency') ?: 'USD');
        $exchangeRate = $adjustment->exchange_rate ?? 1;

        if ($amount < 0) {
            $lines = [
                [
                    'created_by' => 'SYSTEM',
                    'account_id' => $cogsAccountID,
                    'description' => 'Inventory adjustment (Decrease)',
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate,
                    'debit' => abs($amount),
                    'credit' => 0,
                    'debit_dc' => abs($amount) * $exchangeRate,
                    'credit_dc' => 0,
                    'tax_rate_id' => null,
                ],
                [
                    'created_by' => 'SYSTEM',
                    'account_id' => $adjustment->account_id,
                    'description' => 'Inventory adjustment (Decrease)',
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate,
                    'debit' => 0,
                    'credit' => abs($amount),
                    'debit_dc' => 0,
                    'credit_dc' => abs($amount) * $exchangeRate,
                    'tax_rate_id' => null,
                ],
            ];
        } else {
            $lines = [
                [
                    'created_by' => 'SYSTEM',
                    'account_id' => $adjustment->account_id,
                    'description' => 'Inventory adjustment (Increase)',
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate,
                    'debit' => $amount,
                    'credit' => 0,
                    'debit_dc' => $amount * $exchangeRate,
                    'credit_dc' => 0,
                    'tax_rate_id' => null,
                ],
                [
                    'created_by' => 'SYSTEM',
                    'account_id' => $cogsAccountID,
                    'description' => 'Inventory adjustment (Increase)',
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate,
                    'debit' => 0,
                    'credit' => $amount,
                    'debit_dc' => 0,
                    'credit_dc' => $amount * $exchangeRate,
                    'tax_rate_id' => null,
                ],
            ];
        }

        $journal->journal_line_items()->createMany($lines);

        return $journal;
    }

    public function createFromInvoice(Invoice $invoice): Journal
    {
        $currency = $invoice->currency;
        $exchangeRate = $invoice->exchange_rate ?? 1;
        $taxAmountType = $invoice->tax_amount_type; // ['tax_included', 'tax_excluded']


        return DB::transaction(function () use ($invoice, $currency, $exchangeRate, $taxAmountType) {
            $customerAccountId = 7; // Accounts Receivable
            $salesAccountID = 44; // Sales Revenue
            $vatAccountID = 28; // VAT Payable

            // === 1. Create Journal ===
            $journal = $invoice->journals()->create([
                'reference' => $invoice->invoice_number ?? 'auto',
                'date' => $invoice->date,
                'notes' => $invoice->notes,
                'type' => 'auto',
            ]);

            // === 2. Build Lines ===
            $lines = [];

            // (a) Invoice line items

            foreach ($invoice->lineItems as $lineItem) {
                $lineItemSubTotal = $lineItem->quantity * $lineItem->price;
                $discount = ($lineItem->discount ?? 0) * $lineItemSubTotal / 100;
                $lineItemSubTotalAfterDiscount = $lineItemSubTotal - $discount;

                $taxRate = $lineItem->taxRate?->tax_rate ?? 0;
                $vat = 0;

                if ($taxAmountType === 'tax_excluded') {
                    // VAT is added on top
                    $vat = $lineItemSubTotalAfterDiscount * ($taxRate / 100);
                } elseif ($taxAmountType === 'tax_included') {
                    // VAT is part of total price
                    $vat = $lineItemSubTotalAfterDiscount - ($lineItemSubTotalAfterDiscount / (1 + ($taxRate / 100)));
                }

                $total = $lineItemSubTotalAfterDiscount + $vat;

                // === (a.1) Debit Accounts Receivable ===
                $lines[] = [
                    'created_by' => 'SYSTEM',
                    'type' => 'auto',
                    'account_id' => $customerAccountId,
                    'description' => 'Accounts Receivable - ' . $invoice->invoice_number,
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate,
                    'debit' => $total,
                    'credit' => 0,
                    'debit_dc' => $total * $exchangeRate,
                    'credit_dc' => 0,
                    'customer_id' => $invoice->customer_id,
                    'project_id' => $invoice->project_id,
                    'cost_center_id' => $lineItem->cost_center_id,
                ];

                // === (a.2) Credit Sales Revenue ===
                $salesAmount = $lineItemSubTotalAfterDiscount - ($taxAmountType === 'tax_included' ? $vat : 0);

                $lines[] = [
                    'created_by' => 'SYSTEM',
                    'type' => 'auto',
                    'account_id' => $salesAccountID,
                    'description' => 'Sales Revenue - ' . $invoice->invoice_number,
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate,
                    'debit' => 0,
                    'credit' => $salesAmount,
                    'debit_dc' => 0,
                    'credit_dc' => $salesAmount * $exchangeRate,
                    'customer_id' => $invoice->customer_id,
                    'project_id' => $invoice->project_id,
                    'cost_center_id' => $lineItem->cost_center_id,
                ];

                // === (a.3) Credit VAT Payable (if any) ===
                if ($vat > 0) {
                    $lines[] = [
                        'created_by' => 'SYSTEM',
                        'type' => 'auto',
                        'account_id' => $vatAccountID,
                        'description' => 'VAT Payable - ' . $invoice->invoice_number,
                        'currency' => $currency,
                        'exchange_rate' => $exchangeRate,
                        'debit' => 0,
                        'credit' => $vat,
                        'debit_dc' => 0,
                        'credit_dc' => $vat * $exchangeRate,
                        'customer_id' => $invoice->customer_id,
                        'project_id' => $invoice->project_id,
                        'cost_center_id' => $lineItem->cost_center_id,
                    ];
                }
            }

            // (b) Discount
            $discount = $invoice->discount;

            if ($discount && $discount->amount > 0 && $discount->account_id) {
                $discountAmount = $discount->amount;
                $discountTaxRate = $discount->taxRate?->tax_rate ?? 0;
                $discountVat = 0;

                // === Calculate VAT on discount if applicable ===
                if ($discountTaxRate > 0) {
                    $discountVat = $discountAmount * ($discountTaxRate / 100);
                }

                // (1) Debit Sales Discount (Expense)
                $lines[] = [
                    'created_by' => 'SYSTEM',
                    'type' => 'auto',
                    'account_id' => $discount->account_id,
                    'description' => 'Sales Discount - ' . $invoice->invoice_number,
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate,
                    'debit' => $discountAmount,
                    'credit' => 0,
                    'debit_dc' => $discountAmount * $exchangeRate,
                    'credit_dc' => 0,
                    'customer_id' => $invoice->customer_id,
                    'project_id' => $invoice->project_id,
                    'branch_id' => $invoice->branch_id,
                    'cost_center_id' => $invoice->cost_center_id,
                ];

                // (2) Debit VAT Payable (if VAT applies to discount)
                if ($discountVat > 0) {
                    $lines[] = [
                        'created_by' => 'SYSTEM',
                        'type' => 'auto',
                        'account_id' => $vatAccountID,
                        'description' => 'VAT on Sales Discount - ' . $invoice->invoice_number,
                        'currency' => $currency,
                        'exchange_rate' => $exchangeRate,
                        'debit' => $discountVat,
                        'credit' => 0,
                        'debit_dc' => $discountVat * $exchangeRate,
                        'credit_dc' => 0,
                        'customer_id' => $invoice->customer_id,
                        'project_id' => $invoice->project_id,
                        'branch_id' => $invoice->branch_id,
                        'cost_center_id' => $invoice->cost_center_id,
                    ];
                }

                // (3) Credit Accounts Receivable (Customer)
                $totalDiscountCredit = $discountAmount + $discountVat;

                $lines[] = [
                    'created_by' => 'SYSTEM',
                    'type' => 'auto',
                    'account_id' => $customerAccountId,
                    'description' => 'Sales Discount - ' . $invoice->invoice_number,
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate,
                    'debit' => 0,
                    'credit' => $totalDiscountCredit,
                    'debit_dc' => 0,
                    'credit_dc' => $totalDiscountCredit * $exchangeRate,
                    'customer_id' => $invoice->customer_id,
                    'project_id' => $invoice->project_id,
                    'branch_id' => $invoice->branch_id,
                    'cost_center_id' => $invoice->cost_center_id,
                ];
            }


            // (c) Retention
            if ($invoice->retention->amount > 0 && $invoice->retention?->account_id) {
                // Debit
                $lines[] = [
                    'created_by' => 'SYSTEM',
                    'type' => 'auto',
                    'account_id' => $invoice->retention->account_id,
                    'description' => 'Retention - ' . $invoice->invoice_number,
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate,
                    'debit' => $invoice->retention->amount,
                    'credit' => 0,
                    'debit_dc' => $invoice->retention->amount * $exchangeRate,
                    'credit_dc' => 0,
                    'customer_id' => $invoice->customer_id,
                    'project_id' => $invoice->project_id,
                    'branch_id' => $invoice->branch_id,
                    'cost_center_id' => $invoice->cost_center_id,
                ];

                // Credit
                $lines[] = [
                    'created_by' => 'SYSTEM',
                    'type' => 'auto',
                    'account_id' => $customerAccountId,
                    'description' => 'Retention - ' . $invoice->invoice_number,
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate,
                    'debit' => 0,
                    'credit' => $invoice->retention->amount,
                    'debit_dc' => 0,
                    'credit_dc' => $invoice->retention->amount * $exchangeRate,
                    'customer_id' => $invoice->customer_id,
                    'project_id' => $invoice->project_id,
                    'branch_id' => $invoice->branch_id,
                    'cost_center_id' => $invoice->cost_center_id,
                ];
            }

            // === 3. Persist ===
            $journal->journal_line_items()->createMany($lines);

            return $journal;
        });
    }
}
