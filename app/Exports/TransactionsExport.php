<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class TransactionsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
    * This method builds the collection for the export.
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        $query = Transaction::with(['forklift', 'driver', 'warehouseFrom', 'warehouseTo', 'details']);

        if (!empty($this->filters['forklift_id'])) {
            $query->where('forklift_id', $this->filters['forklift_id']);
        }
        if (!empty($this->filters['driver_id'])) {
            $query->where('driver_id', $this->filters['driver_id']);
        }
        if (!empty($this->filters['warehouse_from'])) {
            $query->where('warehouse_from', $this->filters['warehouse_from']);
        }
        if (!empty($this->filters['warehouse_to'])) {
            $query->where('warehouse_to', $this->filters['warehouse_to']);
        }
        if (!empty($this->filters['status']) && $this->filters['status'] !== 'Deleted') {
            $query->where('status', $this->filters['status']);
        } 
        else {
            $query->where('status', '!=', 'Deleted');
        }
        
        if (!empty($this->filters['driver_rating'])) {
            $query->where('driver_rating', $this->filters['driver_rating']);
        }
        
        $transactions = $query->get();


        return $transactions->flatMap(function ($transaction) {
            
            if ($transaction->details->isNotEmpty()) {
                return $transaction->details->map(function ($detail) use ($transaction) {
                    return $this->mapTransactionRow($transaction, $detail);
                });
            }

            return [$this->mapTransactionRow($transaction, null)];
        });
    }

    /**
     * Helper function to map data for a single row.
     * @param Transaction $transaction
     * @param \App\Models\TransactionDetail|null $detail
     * @return array
     */
    protected function mapTransactionRow(Transaction $transaction, $detail): array
    {
        return [
            'Transaction ID'    => $transaction->transaction_id,
            'Status'            => $transaction->status,
            'Item Name'         => optional($detail)->item_name ?? 'N/A',
            'Quantity'          => optional($detail)->quantity ?? 'N/A',
            'Unit'              => optional($detail)->unit ?? 'N/A',
            'Item Remark'       => optional($detail)->description,
            'Warehouse From'    => optional($transaction->warehouseFrom)->name,
            'Warehouse To'      => optional($transaction->warehouseTo)->name,
            'Driver'            => optional($transaction->driver)->name,
            'Forklift'          => optional($transaction->forklift)->model,
            'Driver Rating'     => $transaction->driver_rating,
            'Driver Comment'    => $transaction->driver_comment,
            'Created At (Pending)' => optional($transaction->created_at)->format('Y-m-d H:i:s'),
            'Claimed At'        => optional($transaction->claimed_at)->format('Y-m-d H:i:s'),
            'Started At'        => optional($transaction->started_at)->format('Y-m-d H:i:s'),
            'Arrived At'        => optional($transaction->arrived_at)->format('Y-m-d H:i:s'),
            'Completed At'      => optional($transaction->completed_at)->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Defines the headers for the columns.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Transaction ID',
            'Status',
            'Item Name',
            'Quantity',
            'Unit',
            'Item Remark',
            'Warehouse From',
            'Warehouse To',
            'Driver',
            'Forklift',
            'Driver Rating',
            'Driver Comment',
            'Created At (Pending)',
            'Claimed At',
            'Started At',
            'Arrived At',
            'Completed At',
        ];
    }
}
