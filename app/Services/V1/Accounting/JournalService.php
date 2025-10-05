<?php

namespace App\Services\V1\Accounting;

use App\Models\Tenant\Accounting\Accountants\Journal;
use App\Repositories\V1\Accounting\JournalRepo;
use App\Services\V1\Common\PdfService;
use App\Services\V1\Common\QueryBuilderService;
use App\Services\V1\Common\ReferenceValueFormatterService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class JournalService
{
    public function __construct(
        protected JournalRepo $repo,
        protected PdfService $pdfService,
        protected ReferenceValueFormatterService $formatter,
        protected QueryBuilderService $queryBuilder
    ) {}

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
                    'created_by' => $user_with_email,
                    'account_id' => $item['account_id'],
                    'description' => $item['description'],
                    'currency' => $item['currency'],
                    'exchange_rate' => $item['exchange_rate'] ?? 1,
                    'debit' => $item['debit'],
                    'credit' => $item['credit'],
                    'debit_dc' => $item['debit_dc'],
                    'credit_dc' => $item['credit_dc'],
                    'tax_rate' => $item['tax_rate'] ?? 0,
                    'contact_id' => $item['contact_id'] ?? null,
                    'project_id' => $item['project_id'] ?? null,
                    'branch_id' => $item['branch_id'] ?? null,
                    'cost_center_id' => $item['cost_center_id'] ?? null,
                    'source_type' => 'manual',
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
                    'created_by' => $user_with_email,
                    'account_id' => $item['account_id'],
                    'description' => $item['description'],
                    'currency' => $item['currency'],
                    'exchange_rate' => $item['exchange_rate'] ?? 1,
                    'debit' => $item['debit'],
                    'credit' => $item['credit'],
                    'debit_dc' => $item['debit_dc'],
                    'credit_dc' => $item['credit_dc'],
                    'tax_rate' => $item['tax_rate'] ?? 0,
                    'contact_id' => $item['contact_id'] ?? null,
                    'project_id' => $item['project_id'] ?? null,
                    'branch_id' => $item['branch_id'] ?? null,
                    'cost_center_id' => $item['cost_center_id'] ?? null,
                    'source_type' => 'manual',
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
            $lastName  = $user['last_name'] ?? ($user->last_name ?? '');
            $email     = $user['email'] ?? ($user->email ?? '');
            $name = trim("{$firstName} {$lastName}");
            $user_with_email = $name ? "{$name} - {$email}" : ($email ?: 'UNKNOWN USER');
        }

        return $user_with_email;
    }

    public function createFromAdjustment(array $data): Model
    {
        $amount = $data['amount'];

        $journal = Journal::create([
            'reference' => 'auto',
            'date' => now(),
            'notes' => $data['notes'] ?? null,
            'type' => $data['type'] ?? 'auto',
        ]);

        if ($amount < 0) {
            $lines = [
                [
                    'created_by' => 'SYSTEM',
                    'account_id' => $data['cogs_account_id'],
                    'description' => 'Inventory adjustment (Decrease)',
                    'currency' => $data['currency'],
                    'exchange_rate' => 1,
                    'debit' => abs($amount),
                    'credit' => 0,
                    'debit_dc' => abs($amount),
                    'credit_dc' => 0,
                    'tax_rate_id' => 0,
                    'source_type' => $data['source_type'],
                    'source_id' => $data['source_id'],
                ],
                [
                    'created_by' => 'SYSTEM',
                    'account_id' => $data['inventory_account_id'],
                    'description' => 'Inventory adjustment (Decrease)',
                    'currency' => $data['currency'],
                    'exchange_rate' => 1,
                    'debit' => 0,
                    'credit' => abs($amount),
                    'debit_dc' => 0,
                    'credit_dc' => abs($amount),
                    'tax_rate_id' => 0,
                    'source_type' => $data['source_type'],
                    'source_id' => $data['source_id'],
                ],
            ];
        } else {
            $lines = [
                [
                    'created_by' => 'SYSTEM',
                    'account_id' => $data['inventory_account_id'],
                    'description' => 'Inventory adjustment (Increase)',
                    'currency' => $data['currency'],
                    'exchange_rate' => 1,
                    'debit' => $amount,
                    'credit' => 0,
                    'debit_dc' => $amount,
                    'credit_dc' => 0,
                    'tax_rate_id' => 0,
                    'source_type' => $data['source_type'],
                    'source_id' => $data['source_id'],
                ],
                [
                    'created_by' => 'SYSTEM',
                    'account_id' => $data['cogs_account_id'],
                    'description' => 'Inventory adjustment (Increase)',
                    'currency' => $data['currency'],
                    'exchange_rate' => 1,
                    'debit' => 0,
                    'credit' => $amount,
                    'debit_dc' => 0,
                    'credit_dc' => $amount,
                    'tax_rate_id' => 0,
                    'source_type' => $data['source_type'],
                    'source_id' => $data['source_id'],
                ],
            ];
        }

        $journal->journal_line_items()->createMany($lines);

        return $journal;
    }

}
