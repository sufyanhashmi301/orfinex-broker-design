<?php

namespace App\Exports;

use App\Models\ForexAccount;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ForexStatementMultiSheetExport implements WithMultipleSheets
{
    protected $account;
    protected $statementData;

    public function __construct(ForexAccount $account, array $statementData)
    {
        $this->account = $account;
        $this->statementData = $statementData;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        // Trades Report sheet (combined format like the image)
        $sheets[] = new ForexTradesReportExport($this->account, $this->statementData);
        
        // Summary sheet (detailed account information)
        $sheets[] = new ForexStatementExport($this->account, $this->statementData);
        
        // Open positions sheet (detailed, always include, even if empty)
        $openPositions = $this->statementData['open_positions'] ?? [];
        $sheets[] = new ForexStatementPositionsExport($openPositions, 'open');
        
        // Closed trades sheet (detailed, always include, even if empty)
        $closedTrades = $this->statementData['closed_trades'] ?? [];
        $sheets[] = new ForexStatementPositionsExport($closedTrades, 'closed');
        
        return $sheets;
    }
}

