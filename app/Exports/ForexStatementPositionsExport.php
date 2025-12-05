<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ForexStatementPositionsExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $positions;
    protected $type; // 'open' or 'closed'

    public function __construct(array $positions, string $type = 'open')
    {
        $this->positions = $positions;
        $this->type = $type;
    }

    public function array(): array
    {
        $data = [];
        
        // If no positions/trades, return empty array (headers will still be shown)
        if (empty($this->positions)) {
            return $data;
        }
        
        foreach ($this->positions as $position) {
            if ($this->type === 'open') {
                $data[] = [
                    $position['symbol'] ?? 'N/A',
                    $position['direction'] ?? 'N/A',
                    number_format($position['volume'] ?? 0, 2),
                    number_format($position['open_price'] ?? 0, 5),
                    number_format($position['current_price'] ?? 0, 5),
                    number_format($position['unrealized_pl'] ?? 0, 2),
                ];
            } else {
                $data[] = [
                    $position['deal'] ?? 'N/A',
                    $position['symbol'] ?? 'N/A',
                    $position['direction'] ?? 'N/A',
                    number_format($position['volume'] ?? 0, 2),
                    number_format($position['open_price'] ?? 0, 5),
                    number_format($position['close_price'] ?? 0, 5),
                    $position['open_time'] ?? 'N/A',
                    $position['close_time'] ?? 'N/A',
                    number_format($position['realized_pl'] ?? 0, 2),
                ];
            }
        }
        
        return $data;
    }

    public function headings(): array
    {
        if ($this->type === 'open') {
            return [
                'Symbol',
                'Direction',
                'Volume',
                'Open Price',
                'Current Price',
                'Unrealized P/L',
            ];
        } else {
            return [
                'Deal',
                'Symbol',
                'Direction',
                'Volume',
                'Open Price',
                'Close Price',
                'Open Time',
                'Close Time',
                'Realized P/L',
            ];
        }
    }

    public function title(): string
    {
        return $this->type === 'open' ? 'Open Positions' : 'Closed Trades';
    }

    public function columnWidths(): array
    {
        if ($this->type === 'open') {
            return [
                'A' => 12, 'B' => 12, 'C' => 12,
                'D' => 15, 'E' => 15, 'F' => 15,
            ];
        } else {
            return [
                'A' => 12, 'B' => 12, 'C' => 12, 'D' => 12,
                'E' => 15, 'F' => 15, 'G' => 18, 'H' => 18, 'I' => 15,
            ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Style header row
                $highestColumn = $sheet->getHighestColumn();
                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E0E0E0'],
                    ],
                ]);
                
                // Add message if no data
                if (empty($this->positions)) {
                    $sheet->setCellValue('A2', 'No ' . ($this->type === 'open' ? 'open positions' : 'closed trades') . ' for this period.');
                    $sheet->getStyle('A2')->getFont()->setItalic(true);
                    $sheet->getStyle('A2')->getFont()->setColor(new Color('FF808080'));
                }
            },
        ];
    }
}

