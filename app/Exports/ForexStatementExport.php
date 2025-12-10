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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ForexStatementExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithEvents
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
        
        // Account Summary Section
        $data[] = ['Account Statement'];
        $data[] = [];
        $data[] = ['Account Name', $this->account->account_name];
        $data[] = ['Account Login', $this->account->login];
        $data[] = ['Statement Date', $this->statementData['statement_date']];
        $data[] = ['Account Type', ucfirst($this->account->account_type)];
        $data[] = ['Currency', $this->account->currency];
        $data[] = [];
        
        // Account Balance Section
        $data[] = ['Account Balance'];
        $data[] = ['Opening Balance', number_format($this->statementData['balance']['opening'], 2)];
        $data[] = ['Closing Balance', number_format($this->statementData['balance']['closing'], 2)];
        $data[] = ['Net Change', number_format($this->statementData['balance']['net_change'], 2)];
        $data[] = [];
        
        // Equity & Margin Section
        $data[] = ['Equity, Margin, and Free Margin'];
        $data[] = ['Equity', number_format($this->statementData['equity_margin']['equity'], 2)];
        $data[] = ['Free Margin', number_format($this->statementData['equity_margin']['free_margin'], 2)];
        $data[] = ['Used Margin', number_format($this->statementData['equity_margin']['used_margin'], 2)];
        $data[] = ['Margin Level', number_format($this->statementData['equity_margin']['margin_level'], 2) . '%'];
        $data[] = [];
        
        // Profit & Loss Section
        $data[] = ['Daily Profit & Loss'];
        $data[] = ['Realized P/L', number_format($this->statementData['profit_loss']['realized_pl'], 2)];
        $data[] = ['Unrealized P/L', number_format($this->statementData['profit_loss']['unrealized_pl'], 2)];
        $data[] = ['Total Swap', number_format($this->statementData['profit_loss']['total_swap'], 2)];
        $data[] = ['Total Commission', number_format($this->statementData['profit_loss']['total_commission'], 2)];
        $data[] = ['Net Daily P/L', number_format($this->statementData['profit_loss']['net_daily_pl'], 2)];
        $data[] = [];
        
        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Account Summary';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 20,
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
                
                // Style header row
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                // Style section headers
                $sectionRows = [9, 15, 20, 26]; // Row numbers for section headers
                foreach ($sectionRows as $row) {
                    $sheet->getStyle("A{$row}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E0E0E0'],
                        ],
                    ]);
                }
                
                // Merge cells for title
                $sheet->mergeCells('A1:B1');
            },
        ];
    }
}

