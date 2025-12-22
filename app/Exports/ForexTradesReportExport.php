<?php

namespace App\Exports;

use App\Models\ForexAccount;
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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ForexTradesReportExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $account;
    protected $statementData;

    public function __construct(ForexAccount $account, array $statementData)
    {
        $this->account = $account;
        $this->statementData = $statementData;
    }

    public function array(): array
    {
        $data = [];
        
        // Account Info Header
        $data[] = ['Account Statement'];
        $data[] = [];
        $data[] = ['Account Name', $this->account->account_name];
        $data[] = ['Account Login', $this->account->login];
        $data[] = ['Statement Date', $this->statementData['statement_date']];
        $data[] = ['Account Balance', number_format($this->statementData['balance']['closing'], 2) . ' ' . $this->account->currency];
        $data[] = ['Equity', number_format($this->statementData['equity_margin']['equity'], 2) . ' ' . $this->account->currency];
        $data[] = ['Free Margin', number_format($this->statementData['equity_margin']['free_margin'], 2) . ' ' . $this->account->currency];
        $data[] = [];
        
        // Open Positions Section
        $data[] = ['Open Positions'];
        $data[] = ['Symbol', 'Direction', 'Volume', 'Open Price', 'Current Price', 'Unrealized P/L'];
        
        $openPositions = $this->statementData['open_positions'] ?? [];
        if (empty($openPositions)) {
            $data[] = ['No open positions', '', '', '', '', ''];
        } else {
            foreach ($openPositions as $position) {
                $data[] = [
                    $position['symbol'] ?? 'N/A',
                    $position['direction'] ?? 'N/A',
                    number_format($position['volume'] ?? 0, 2),
                    number_format($position['open_price'] ?? 0, 5),
                    number_format($position['current_price'] ?? 0, 5),
                    number_format($position['unrealized_pl'] ?? 0, 2),
                ];
            }
        }
        
        $data[] = [];
        $data[] = [];
        
        // Closed Trades Section
        $data[] = ['Closed Trades'];
        $data[] = ['Deal', 'Symbol', 'Direction', 'Volume', 'Open Price', 'Close Price', 'Realized P/L'];
        
        $closedTrades = $this->statementData['closed_trades'] ?? [];
        if (empty($closedTrades)) {
            $data[] = ['No closed trades', '', '', '', '', '', ''];
        } else {
            foreach ($closedTrades as $trade) {
                $data[] = [
                    $trade['deal'] ?? 'N/A',
                    $trade['symbol'] ?? 'N/A',
                    $trade['direction'] ?? 'N/A',
                    number_format($trade['volume'] ?? 0, 2),
                    number_format($trade['open_price'] ?? 0, 5),
                    number_format($trade['close_price'] ?? 0, 5),
                    number_format($trade['realized_pl'] ?? 0, 2),
                ];
            }
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Trades Report';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 12,
            'C' => 12,
            'D' => 12,
            'E' => 15,
            'F' => 15,
            'G' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Find row numbers for sections
                $openPositionsHeaderRow = null;
                $closedTradesHeaderRow = null;
                
                for ($row = 1; $row <= $sheet->getHighestRow(); $row++) {
                    $cellValue = $sheet->getCell("A{$row}")->getValue();
                    if ($cellValue === 'Open Positions') {
                        $openPositionsHeaderRow = $row;
                    }
                    if ($cellValue === 'Closed Trades') {
                        $closedTradesHeaderRow = $row;
                    }
                }
                
                // Style main title
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->mergeCells('A1:G1');
                
                // Style Open Positions header
                if ($openPositionsHeaderRow) {
                    $sheet->getStyle("A{$openPositionsHeaderRow}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 14],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E0E0E0'],
                        ],
                    ]);
                    $sheet->mergeCells("A{$openPositionsHeaderRow}:G{$openPositionsHeaderRow}");
                    
                    // Style Open Positions table header row
                    $headerRow = $openPositionsHeaderRow + 1;
                    $sheet->getStyle("A{$headerRow}:F{$headerRow}")->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F0F0F0'],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                    
                    // Style Open Positions data rows
                    $dataStartRow = $headerRow + 1;
                    $lastRow = $closedTradesHeaderRow ? $closedTradesHeaderRow - 2 : $sheet->getHighestRow();
                    for ($row = $dataStartRow; $row <= $lastRow; $row++) {
                        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                            ],
                        ]);
                    }
                }
                
                // Style Closed Trades header
                if ($closedTradesHeaderRow) {
                    $sheet->getStyle("A{$closedTradesHeaderRow}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 14],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E0E0E0'],
                        ],
                    ]);
                    $sheet->mergeCells("A{$closedTradesHeaderRow}:G{$closedTradesHeaderRow}");
                    
                    // Style Closed Trades table header row
                    $headerRow = $closedTradesHeaderRow + 1;
                    $sheet->getStyle("A{$headerRow}:G{$headerRow}")->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F0F0F0'],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                    
                    // Style Closed Trades data rows
                    $dataStartRow = $headerRow + 1;
                    $lastRow = $sheet->getHighestRow();
                    for ($row = $dataStartRow; $row <= $lastRow; $row++) {
                        $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                            ],
                        ]);
                    }
                }
                
                // Center align numeric columns
                $sheet->getStyle('D:G')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);
            },
        ];
    }
}

