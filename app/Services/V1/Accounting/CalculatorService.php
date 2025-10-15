<?php

namespace App\Services\V1\Accounting;

use App\Models\Tenant\Accounting\Accountants\TaxRate;
use App\Models\Tenant\Accounting\Discount\Discount;

class CalculatorService
{
    public function calculateTotals(array $data): array
    {
        $subtotal = 0;
        $totalTax = 0;

        foreach ($data['line_items'] as $item) {
            $qty = $item['quantity'];
            $price = $item['price'];
            $discount = ($item['discount_percent'] * $qty * $price) / 100;
            $lineTotal = ($qty * $price) - $discount;

            $taxRate = 0;
            if (!empty($item['tax_rate_id'])) {
                $taxRate = TaxRate::find($item['tax_rate_id'])?->tax_rate ?? 0;
            }

            if (($data['tax_amount_type'] ?? 'tax_excluded') === 'tax_included') {
                // Price includes tax
                $basePrice = $lineTotal / (1 + ($taxRate / 100));
                $vat = $lineTotal - $basePrice;
            } else {
                // Price excludes tax
                $basePrice = $lineTotal;
                $vat = $basePrice * ($taxRate / 100);
            }

            $subtotal += $basePrice;
            $totalTax += $vat;
        }

        // === Discount ===
        $discount = $data['discount']['amount'] ?? 0;
        $discountTaxRate = TaxRate::find($data['discount']['tax_rate_id'])->tax_rate * $discount / 100 ?? 0;
        $discountExecVat = $discount - $discountTaxRate;


        // === Retention ===
        $retentionAmount = $data['retention']['amount'] ?? 0;

        // === Final Totals ===
        $total = $subtotal + $totalTax - $discountExecVat - $retentionAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'vat' => round($totalTax, 2),
            'discount_exec_vat' => round($discountExecVat, 2),
            'retention_amount' => round($retentionAmount, 2),
            'total' => round($total, 2),
            'net_due' => round($total, 2),
        ];
    }
}
