<?php
require_once __DIR__ . '/../config/database.php';
session_start();
$conn = getConnection();

// Get report type
$report_type = isset($_GET['type']) ? $_GET['type'] : 'trial_balance';

// Handle date range filters
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Helper functions
function formatMoney($amount, $decimals = 0) {
    return number_format(round($amount, $decimals), $decimals, '.', ',');
}

function roundAmount($amount, $decimals = 2) {
    return round($amount, $decimals);
}

// ========================================
// CORE FUNCTION: Calculate Trial Balance
// ========================================
function calculateTrialBalance($conn, $start_date, $end_date) {
    $trial_data = [];
    
    // Get all accounts from chart_of_accounts
    $accounts_sql = "SELECT account_code, account_name, class, normal_balance 
                    FROM chart_of_accounts 
                    WHERE is_active = 1 
                    ORDER BY class, account_code";
    $accounts_result = mysqli_query($conn, $accounts_sql);
    
    if (!$accounts_result) {
        error_log("Error fetching accounts: " . mysqli_error($conn));
        return [];
    }
    
    while ($account = mysqli_fetch_assoc($accounts_result)) {
        $account_code = mysqli_real_escape_string($conn, $account['account_code']);
        $account_name = $account['account_name'];
        $class = $account['class'];
        $normal_balance = $account['normal_balance'];
        
        // ==========================================
        // STEP 1: Get OPENING BALANCE (before start_date)
        // ==========================================
        $opening_sql = "SELECT 
                        SUM(debit_amount) as total_debit,
                        SUM(credit_amount) as total_credit
                        FROM ledger 
                        WHERE account_code = '$account_code' 
                        AND transaction_date < '$start_date'";
        $opening_result = mysqli_query($conn, $opening_sql);
        
        $opening_debit = 0;
        $opening_credit = 0;
        if ($opening_result && mysqli_num_rows($opening_result) > 0) {
            $row = mysqli_fetch_assoc($opening_result);
            $opening_debit = roundAmount(floatval($row['total_debit'] ?? 0));
            $opening_credit = roundAmount(floatval($row['total_credit'] ?? 0));
        }
        
        // Calculate initial balance (opening balance)
        $initial_balance = $opening_debit - $opening_credit;
        $initial_balance = roundAmount($initial_balance);
        
        // ==========================================
        // STEP 2: Get PERIOD MOVEMENTS (between start and end date)
        // ==========================================
        $movement_sql = "SELECT 
                         SUM(debit_amount) as period_debit,
                         SUM(credit_amount) as period_credit
                         FROM ledger 
                         WHERE account_code = '$account_code' 
                         AND transaction_date BETWEEN '$start_date' AND '$end_date'";
        $movement_result = mysqli_query($conn, $movement_sql);
        
        $period_debit = 0;
        $period_credit = 0;
        if ($movement_result && mysqli_num_rows($movement_result) > 0) {
            $row = mysqli_fetch_assoc($movement_result);
            $period_debit = roundAmount(floatval($row['period_debit'] ?? 0));
            $period_credit = roundAmount(floatval($row['period_credit'] ?? 0));
        }
        
        // ==========================================
        // STEP 3: Calculate CLOSING BALANCE
        // ==========================================
        // Closing Balance = Initial Balance + Period Debit - Period Credit
        $closing_balance = $initial_balance + $period_debit - $period_credit;
        $closing_balance = roundAmount($closing_balance);
        
        $trial_data[] = [
            'account_code' => $account_code,
            'account_name' => $account_name,
            'class' => $class,
            'normal_balance' => $normal_balance,
            'initial_balance' => $initial_balance,  // Opening balance
            'period_debit' => $period_debit,       // Movements - Debit
            'period_credit' => $period_credit,     // Movements - Credit
            'closing_balance' => $closing_balance,  // Final balance
            'is_calculated' => false
        ];
    }
    
    return $trial_data;
}

// Get trial balance data first
$trial_data = calculateTrialBalance($conn, $start_date, $end_date);

// ========================================
// Calculate Current Period Earnings/Loss and Retained Earnings (for Trial Balance only)
// ========================================
$total_revenues = 0;
$total_expenses = 0;
$current_period_earnings = 0;
$previous_total_profit_loss = 0;
$retained_earnings = 0;

// Calculate revenues and expenses for CURRENT PERIOD
foreach ($trial_data as $account) {
    $account_code = $account['account_code'];
    $first_digit = substr($account_code, 0, 1);
    $closing_balance = $account['closing_balance'];
    
    // Revenue accounts (4xxx) - Credit balances are revenues
    if ($first_digit == '4') {
        // Revenue has negative balance (credit), so we take absolute value
        $total_revenues += abs($closing_balance);
    }
    
    // Expense accounts (5xxx, 6xxx) - Debit balances are expenses
    if ($first_digit == '5' || $first_digit == '6') {
        // Expenses have positive balance (debit)
        $total_expenses += $closing_balance;
    }
}

// Current Period Earnings/Loss = Total Revenues - Total Expenses
$current_period_earnings = roundAmount($total_revenues - $total_expenses);

// Calculate TOTAL PROFIT/LOSS (from beginning of time to end_date)
$escaped_end_date = mysqli_real_escape_string($conn, $end_date);
$total_profit_loss_sql = "SELECT 
    SUM(CASE WHEN SUBSTRING(account_code, 1, 1) = '4' THEN credit_amount - debit_amount ELSE 0 END) as total_revenue,
    SUM(CASE WHEN SUBSTRING(account_code, 1, 1) IN ('5', '6') THEN debit_amount - credit_amount ELSE 0 END) as total_expense
    FROM ledger 
    WHERE transaction_date <= '$escaped_end_date'";

$total_pl_result = mysqli_query($conn, $total_profit_loss_sql);
if ($total_pl_result && mysqli_num_rows($total_pl_result) > 0) {
    $pl_row = mysqli_fetch_assoc($total_pl_result);
    $cumulative_revenue = roundAmount(floatval($pl_row['total_revenue'] ?? 0));
    $cumulative_expense = roundAmount(floatval($pl_row['total_expense'] ?? 0));
    $previous_total_profit_loss = roundAmount($cumulative_revenue - $cumulative_expense);
}

// Retained Earnings = Total Profit/Loss - Current Period Earnings/Loss
$retained_earnings = roundAmount($previous_total_profit_loss - $current_period_earnings);

// Define class order for TRIAL BALANCE (exact order as specified)
$trial_balance_class_order = [
    'Assets' => 1,
    'Fixed Assets' => 2,
    'Liabilities' => 3,
    'Liabilites' => 3, // Handle typo in data
    'Equity' => 4,
    'Revenue' => 5,
    'Fee Income' => 6,
    'Fee Income ' => 6, // Handle extra space
    'Operating Expenses' => 7,
    'Operating  Expenses' => 7, // Handle double space
    'Board Sitting Allowances Expenses' => 8,
    'Board Sitting Allowances Expenses ' => 8, // Handle extra space
    'Professional & Legal Fees Expenses' => 9,
    'Financial Expenses' => 10,
    'Depreciation & Provisions Expense' => 11
];

// Sort trial balance data by class order
usort($trial_data, function($a, $b) use ($trial_balance_class_order) {
    $a_class = $a['class'];
    $b_class = $b['class'];
    $a_order = isset($trial_balance_class_order[$a_class]) ? $trial_balance_class_order[$a_class] : 999;
    $b_order = isset($trial_balance_class_order[$b_class]) ? $trial_balance_class_order[$b_class] : 999;
    
    if ($a_order == $b_order) {
        return strcmp($a['account_code'], $b['account_code']);
    }
    return $a_order - $b_order;
});

// Now insert the calculated accounts BETWEEN Balance Sheet (1,2,3) and Income Statement (4,5,6) sections
// Find the position after last Equity account
$insert_position = 0;
foreach ($trial_data as $index => $account) {
    $first_digit = substr($account['account_code'], 0, 1);
    if ($first_digit == '3') {
        $insert_position = $index + 1;
    }
}

// Remove any existing 3101 or 3102 accounts to avoid duplicates
$trial_data = array_filter($trial_data, function($account) {
    return !in_array($account['account_code'], ['3101', '3102']);
});
$trial_data = array_values($trial_data); // Re-index array

// Recalculate insert position after filtering
$insert_position = 0;
foreach ($trial_data as $index => $account) {
    $first_digit = substr($account['account_code'], 0, 1);
    if ($first_digit == '3') {
        $insert_position = $index + 1;
    }
}

// Insert Retained Earnings first (so it appears before Current Period Earnings after insertion)
array_splice($trial_data, $insert_position, 0, [[
    'account_code' => '3102',
    'account_name' => 'Retained Earnings',
    'class' => 'Equity',
    'normal_balance' => 'Credit',
    'initial_balance' => 0,
    'period_debit' => 0,
    'period_credit' => 0,
    'closing_balance' => -$retained_earnings,  // Negative because it's equity (credit)
    'is_calculated' => true
]]);

// Insert Current Period Earnings/Loss after Retained Earnings
array_splice($trial_data, $insert_position + 1, 0, [[
    'account_code' => '3101',
    'account_name' => 'Current Period Earnings/Loss',
    'class' => 'Equity',
    'normal_balance' => 'Credit',
    'initial_balance' => 0,
    'period_debit' => 0,
    'period_credit' => 0,
    'closing_balance' => -$current_period_earnings,  // Negative because it's equity (credit)
    'is_calculated' => true
]]);

// Initialize report variables
$report_data = [];
$report_title = "";
$total_assets = 0;
$total_liabilities = 0;
$total_equity = 0;
$total_revenue = 0;
$total_expenses = 0;
$net_income = 0;

// Variables for balance sheet two-column layout
$assets_data = [];
$liabilities_data = [];
$equity_data = [];

// ========================================
// Calculate Net Income from Trial Balance
// ========================================
$net_income_from_trial = 0;
foreach ($trial_data as $account) {
    $class = $account['class'];
    $closing_balance = $account['closing_balance'];
    $first_digit = substr($account['account_code'], 0, 1);
    
    // Revenue accounts (4xxx) - Credits increase income
    if ($first_digit == '4' || stripos($class, 'Revenue') !== false || stripos($class, 'Fee Income') !== false) {
        $net_income_from_trial -= $closing_balance; // Negative balance = revenue (credit)
    }
    
    // Expense accounts (5xxx, 6xxx) - Debits increase expenses
    if ($first_digit == '5' || $first_digit == '6' || 
        stripos($class, 'Expenses') !== false || stripos($class, 'Expense') !== false) {
        $net_income_from_trial -= $closing_balance; // Positive balance = expense (debit)
    }
}
$net_income_from_trial = roundAmount($net_income_from_trial);

// ========================================
// Generate Reports Based on Type
// ========================================
switch ($report_type) {
    case 'trial_balance':
        $report_title = "Trial Balance";
        $report_data = $trial_data;
        break;
        
    case 'balance_sheet':
        $report_title = "Balance Sheet";
        
        // Separate arrays for each section
        $assets_data = [];
        $liabilities_data = [];
        $equity_data = [];
        
        // Balance Sheet: Assets (1), Liabilities (2), Equity (3)
        foreach ($trial_data as $account) {
            $first_digit = substr($account['account_code'], 0, 1);
            
            $closing_balance = $account['closing_balance'];
            
            // Separate into categories
            if ($first_digit == '1') {
                $assets_data[] = $account;
                $total_assets += $closing_balance;
            } elseif ($first_digit == '2') {
                $liabilities_data[] = $account;
                $total_liabilities += $closing_balance;
            } elseif ($first_digit == '3') {
                $equity_data[] = $account;
                $total_equity += $closing_balance;
            }
        }
        
        // Sort each section by account code
        usort($assets_data, function($a, $b) {
            return strcmp($a['account_code'], $b['account_code']);
        });
        usort($liabilities_data, function($a, $b) {
            return strcmp($a['account_code'], $b['account_code']);
        });
        usort($equity_data, function($a, $b) {
            return strcmp($a['account_code'], $b['account_code']);
        });
        
        // Store in report_data for compatibility
        $report_data = array_merge($assets_data, $equity_data, $liabilities_data);
        break;
        
    case 'income_statement':
        $report_title = "Income Statement";
        
        // Income Statement: Revenue (4), Expenses (5, 6)
        foreach ($trial_data as $account) {
            $first_digit = substr($account['account_code'], 0, 1);
            
            // Only include income statement accounts
            if ($first_digit == '4' || $first_digit == '5' || $first_digit == '6') {
                $report_data[] = $account;
                
                $closing_balance = $account['closing_balance'];
                
                // Calculate totals
                if ($first_digit == '4') {
                    $total_revenue += abs($closing_balance); // Revenue is negative (credit)
                } elseif ($first_digit == '5' || $first_digit == '6') {
                    $total_expenses += $closing_balance; // Expense is positive (debit)
                }
            }
        }
        
        // Calculate net income
        $net_income = $total_revenue - $total_expenses;
        $net_income = roundAmount($net_income);
        
        // Sort: Revenue first, then Expenses
        usort($report_data, function($a, $b) {
            return strcmp($a['account_code'], $b['account_code']);
        });
        break;
        
    default:
        $report_title = "Trial Balance";
        $report_data = $trial_data;
        break;
}

// Validate date range
if (strtotime($start_date) > strtotime($end_date)) {
    $temp = $start_date;
    $start_date = $end_date;
    $end_date = $temp;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .report-header {
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .table th {
            position: sticky;
            top: 0;
            background: #f8f9fa;
            z-index: 10;
        }
        .amount-cell {
            text-align: right;
            font-family: 'Courier New', monospace;
        }
        .positive-balance {
            color: #198754;
        }
        .negative-balance {
            color: #dc3545;
        }
        .group-header {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .total-row {
            background-color: #f8f9fa !important;
            font-weight: bold;
            border-top: 2px solid #000;
        }
        .grand-total-row {
            background-color: #e9ecef !important;
            font-weight: bold;
            border-top: 3px double #000;
            border-bottom: 3px double #000;
        }
        .account-code {
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        .timeframe-filter {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .nav-tabs .nav-link.active {
            background-color: #f8f9fa;
            border-bottom-color: #f8f9fa;
            font-weight: bold;
        }
        .quick-date-btn {
            border-radius: 5px;
            margin: 2px;
        }
        .financial-summary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #dee2e6;
        }
        .accounting-equation {
            background: #d1ecf1;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #bee5eb;
        }
        /* Two-column balance sheet styles */
        .balance-sheet-table {
            width: 100%;
            table-layout: fixed;
        }
        .balance-sheet-table td {
            vertical-align: top;
            padding: 0 10px;
            width: 50%;
        }
        .balance-sheet-section {
            width: 100%;
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }
        .balance-sheet-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .balance-sheet-items {
            flex: 1;
        }
        .section-header {
            background-color: #667eea;
            color: white;
            font-weight: bold;
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
        }
        .subsection-header {
            background-color: #e9ecef;
            font-weight: bold;
            padding: 8px;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        .balance-sheet-row {
            padding: 5px 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .balance-sheet-total {
            background-color: #f8f9fa;
            font-weight: bold;
            padding: 10px;
            border-top: 2px solid #000;
            margin-top: auto;
        }
        .equity-liabilities-wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .equity-section {
            flex: 0 0 auto;
        }
        .liabilities-section {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .section-spacer {
            flex: 1;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Report Header -->
        <div class="row mb-4 no-print">
            <div class="col-12">
                <h2 class="h4 fw-bold text-primary">Financial Reports</h2>
                <p class="text-muted">Generate and view financial reports from your General Ledger</p>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="row mb-3 no-print">
            <div class="col-12">
                <div class="timeframe-filter">
                    <h5><i class="fas fa-calendar-alt me-2"></i>Report Period</h5>
                    <form method="GET" action="" class="row g-3 align-items-end" id="reportFilter">
                        <input type="hidden" name="page" value="financial_reports">
                        <input type="hidden" name="type" value="<?php echo htmlspecialchars($report_type); ?>">
                        
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" 
                                   value="<?php echo htmlspecialchars($start_date); ?>" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" 
                                   value="<?php echo htmlspecialchars($end_date); ?>" required>
                        </div>
                        
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Apply Filter
                            </button>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Quick Select:</label>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-secondary quick-date-btn" onclick="setDateRange('today')">
                                    Today
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary quick-date-btn" onclick="setDateRange('month')">
                                    This Month
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary quick-date-btn" onclick="setDateRange('year')">
                                    This Year
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Report Navigation Tabs -->
        <div class="row mb-4 no-print">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $report_type == 'trial_balance' ? 'active' : ''; ?>" 
                                        type="button" onclick="switchReportType('trial_balance')">
                                    <i class="fas fa-balance-scale me-1"></i> Trial Balance
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $report_type == 'balance_sheet' ? 'active' : ''; ?>" 
                                        type="button" onclick="switchReportType('balance_sheet')">
                                    <i class="fas fa-file-invoice me-1"></i> Balance Sheet
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $report_type == 'income_statement' ? 'active' : ''; ?>" 
                                        type="button" onclick="switchReportType('income_statement')">
                                    <i class="fas fa-chart-line me-1"></i> Income Statement
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Content -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center no-print">
                        <h5 class="mb-0">
                            <i class="fas <?php 
                                echo $report_type == 'trial_balance' ? 'fa-balance-scale' : 
                                     ($report_type == 'balance_sheet' ? 'fa-file-invoice' : 'fa-chart-line'); 
                            ?> me-2"></i>
                            <?php echo htmlspecialchars($report_title); ?>
                        </h5>
                        <div>
                            <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn btn-outline-primary btn-sm ms-2" onclick="exportToExcel()">
                                <i class="fas fa-download"></i> Export to Excel
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Report Header Info -->
                        <div class="report-header text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h4 class="text-white">Accounting & Loan Management System</h4>
                            <h5 class="text-white"><?php echo htmlspecialchars($report_title); ?></h5>
                            <p class="text-white">
                                <?php if ($report_type == 'balance_sheet'): ?>
                                    As of <?php echo date('F d, Y', strtotime($end_date)); ?>
                                <?php else: ?>
                                    Period: <?php echo date('F d, Y', strtotime($start_date)); ?> 
                                    to <?php echo date('F d, Y', strtotime($end_date)); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        
                        <?php if (empty($report_data)): ?>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i> No data found for the selected period.
                        </div>
                        <?php else: ?>
                        
                        <?php if ($report_type == 'balance_sheet'): ?>
                        <!-- TWO-COLUMN BALANCE SHEET LAYOUT -->
                        <div class="mt-3">
                            <table class="balance-sheet-table" id="reportTable">
                                <tr>
                                    <!-- LEFT COLUMN: ASSETS -->
                                    <td>
                                        <div class="balance-sheet-section">
                                            <div class="section-header">ASSETS</div>
                                            <div class="balance-sheet-content">
                                                <div class="balance-sheet-items">
                                                    <?php 
                                                    $current_asset_class = '';
                                                    foreach ($assets_data as $asset): 
                                                        // Display class header when it changes
                                                        if ($asset['class'] != $current_asset_class):
                                                            $current_asset_class = $asset['class'];
                                                    ?>
                                                    <div class="subsection-header"><?php echo htmlspecialchars($current_asset_class); ?></div>
                                                    <?php endif; ?>
                                                    <div class="balance-sheet-row d-flex justify-content-between">
                                                        <div>
                                                            <span class="account-code"><?php echo htmlspecialchars($asset['account_code']); ?></span>
                                                            <?php echo htmlspecialchars($asset['account_name']); ?>
                                                        </div>
                                                        <div class="amount-cell">
                                                            <?php echo formatMoney($asset['closing_balance']); ?>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                
                                                <div class="balance-sheet-total d-flex justify-content-between">
                                                    <div><strong>TOTAL ASSETS</strong></div>
                                                    <div class="amount-cell"><strong><?php echo formatMoney($total_assets); ?></strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- RIGHT COLUMN: EQUITY & LIABILITIES -->
                                    <td>
                                        <div class="balance-sheet-section">
                                            <div class="equity-liabilities-wrapper">
                                                <!-- EQUITY SECTION -->
                                                <div class="equity-section">
                                                    <div class="section-header">OWNER'S EQUITY</div>
                                                    <?php 
                                                    $current_equity_class = '';
                                                    foreach ($equity_data as $equity): 
                                                        // Display class header when it changes
                                                        if ($equity['class'] != $current_equity_class):
                                                            $current_equity_class = $equity['class'];
                                                    ?>
                                                    <div class="subsection-header"><?php echo htmlspecialchars($current_equity_class); ?></div>
                                                    <?php endif; ?>
                                                    <div class="balance-sheet-row d-flex justify-content-between">
                                                        <div>
                                                            <span class="account-code"><?php echo htmlspecialchars($equity['account_code']); ?></span>
                                                            <?php echo htmlspecialchars($equity['account_name']); ?>
                                                        </div>
                                                        <div class="amount-cell">
                                                            <?php echo formatMoney(abs($equity['closing_balance'])); ?>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                    
                                                    <div class="balance-sheet-total d-flex justify-content-between">
                                                        <div><strong>TOTAL EQUITY</strong></div>
                                                        <div class="amount-cell"><strong><?php echo formatMoney(abs($total_equity)); ?></strong></div>
                                                    </div>
                                                </div>
                                                
                                                <!-- LIABILITIES SECTION -->
                                                <div class="liabilities-section">
                                                    <div class="section-header mt-4">LIABILITIES</div>
                                                    <div class="balance-sheet-items">
                                                        <?php 
                                                        $current_liability_class = '';
                                                        foreach ($liabilities_data as $liability): 
                                                            // Display class header when it changes
                                                            if ($liability['class'] != $current_liability_class):
                                                                $current_liability_class = $liability['class'];
                                                        ?>
                                                        <div class="subsection-header"><?php echo htmlspecialchars($current_liability_class); ?></div>
                                                        <?php endif; ?>
                                                        <div class="balance-sheet-row d-flex justify-content-between">
                                                            <div>
                                                                <span class="account-code"><?php echo htmlspecialchars($liability['account_code']); ?></span>
                                                                <?php echo htmlspecialchars($liability['account_name']); ?>
                                                            </div>
                                                            <div class="amount-cell">
                                                                <?php echo formatMoney(abs($liability['closing_balance'])); ?>
                                                            </div>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    
                                                    <div class="balance-sheet-total d-flex justify-content-between">
                                                        <div><strong>TOTAL LIABILITIES</strong></div>
                                                        <div class="amount-cell"><strong><?php echo formatMoney(abs($total_liabilities)); ?></strong></div>
                                                    </div>
                                                    
                                                    <!-- TOTAL EQUITY + LIABILITIES -->
                                                    <div class="balance-sheet-total d-flex justify-content-between mt-3" style="border-top: 3px double #000;">
                                                        <div><strong>TOTAL EQUITY & LIABILITIES</strong></div>
                                                        <div class="amount-cell"><strong><?php echo formatMoney(abs($total_equity) + abs($total_liabilities)); ?></strong></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <?php elseif ($report_type == 'trial_balance'): ?>
                        <!-- TRIAL BALANCE TABLE -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-sm table-hover" id="reportTable">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2" style="vertical-align: middle;">Group</th>
                                        <th rowspan="2" style="vertical-align: middle;">Account Code</th>
                                        <th rowspan="2" style="vertical-align: middle;">Account Name</th>
                                        <th colspan="3" class="text-center" style="border-bottom: 2px solid #dee2e6;">Opening Balance</th>
                                        <th colspan="2" class="text-center" style="border-bottom: 2px solid #dee2e6;">Movements</th>
                                        <th colspan="2" class="text-center" style="border-bottom: 2px solid #dee2e6;">Closing Balance</th>
                                    </tr>
                                    <tr>
                                        <th class="text-end">Initial Balance</th>
                                        <th class="text-end">Debit</th>
                                        <th class="text-end">Credit</th>
                                        <th class="text-end">Debit</th>
                                        <th class="text-end">Credit</th>
                                        <th class="text-end">Balance</th>
                                        <th class="text-end">Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Initialize totals
                                    $grand_initial_balance = 0;
                                    $grand_initial_debit = 0;
                                    $grand_initial_credit = 0;
                                    $grand_movement_debit = 0;
                                    $grand_movement_credit = 0;
                                    $grand_closing_balance = 0;
                                    $grand_closing_debit = 0;
                                    $grand_closing_credit = 0;
                                    
                                    // Group totals
                                    $current_class = '';
                                    $class_initial_balance = 0;
                                    $class_initial_debit = 0;
                                    $class_initial_credit = 0;
                                    $class_movement_debit = 0;
                                    $class_movement_credit = 0;
                                    $class_closing_balance = 0;
                                    $class_closing_debit = 0;
                                    $class_closing_credit = 0;
                                    
                                    foreach ($report_data as $index => $row): 
                                        $initial_balance = $row['initial_balance'];
                                        $period_debit = $row['period_debit'];
                                        $period_credit = $row['period_credit'];
                                        $closing_balance = $row['closing_balance'];
                                        
                                        // Calculate display values for opening balance
                                        $initial_debit = $initial_balance > 0 ? $initial_balance : 0;
                                        $initial_credit = $initial_balance < 0 ? abs($initial_balance) : 0;
                                        
                                        // Movements stay as is
                                        $movement_debit = $period_debit;
                                        $movement_credit = $period_credit;
                                        
                                        // Calculate display values for closing balance
                                        $closing_debit = $closing_balance > 0 ? $closing_balance : 0;
                                        $closing_credit = $closing_balance < 0 ? abs($closing_balance) : 0;
                                        
                                        // Display class header when class changes
                                        if ($row['class'] != $current_class):
                                            // Print class total if not first class
                                            if ($current_class != ''):
                                    ?>
                                    <tr class="table-secondary fw-bold">
                                        <td colspan="3" class="text-end"><?php echo htmlspecialchars($current_class); ?> Total:</td>
                                        <td class="text-end"><?php echo formatMoney($class_initial_balance); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_initial_debit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_initial_credit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_movement_debit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_movement_credit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_closing_balance); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_closing_balance); ?></td>
                                    </tr>
                                    <?php 
                                                // Reset class totals
                                                $class_initial_balance = 0;
                                                $class_initial_debit = 0;
                                                $class_initial_credit = 0;
                                                $class_movement_debit = 0;
                                                $class_movement_credit = 0;
                                                $class_closing_balance = 0;
                                                $class_closing_debit = 0;
                                                $class_closing_credit = 0;
                                            endif;
                                            
                                            $current_class = $row['class'];
                                        endif;
                                        
                                        // Accumulate class totals
                                        $class_initial_balance += $initial_balance;
                                        $class_initial_debit += $initial_debit;
                                        $class_initial_credit += $initial_credit;
                                        $class_movement_debit += $movement_debit;
                                        $class_movement_credit += $movement_credit;
                                        $class_closing_balance += $closing_balance;
                                        $class_closing_debit += $closing_debit;
                                        $class_closing_credit += $closing_credit;
                                        
                                        // Accumulate grand totals
                                        $grand_initial_balance += $initial_balance;
                                        $grand_initial_debit += $initial_debit;
                                        $grand_initial_credit += $initial_credit;
                                        $grand_movement_debit += $movement_debit;
                                        $grand_movement_credit += $movement_credit;
                                        $grand_closing_balance += $closing_balance;
                                        $grand_closing_debit += $closing_debit;
                                        $grand_closing_credit += $closing_credit;
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['class']); ?></td>
                                        <td>
                                            <span class="account-code"><?php echo htmlspecialchars($row['account_code']); ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                                        <td class="text-end amount-cell"><?php echo formatMoney($initial_balance); ?></td>
                                        <td class="text-end amount-cell"><?php echo formatMoney($initial_debit); ?></td>
                                        <td class="text-end amount-cell"><?php echo formatMoney($initial_credit); ?></td>
                                        <td class="text-end amount-cell"><?php echo formatMoney($movement_debit); ?></td>
                                        <td class="text-end amount-cell"><?php echo formatMoney($movement_credit); ?></td>
                                        <td class="text-end amount-cell <?php echo $closing_balance > 0 ? 'text-success' : ($closing_balance < 0 ? 'text-danger' : ''); ?>">
                                            <?php echo formatMoney($closing_balance); ?>
                                        </td>
                                        <td class="text-end amount-cell fw-bold <?php echo $closing_balance > 0 ? 'text-success' : ($closing_balance < 0 ? 'text-danger' : ''); ?>">
                                            <?php echo formatMoney($closing_balance); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; 
                                    
                                    // Print final class total
                                    if ($current_class != ''):
                                    ?>
                                    <tr class="table-secondary fw-bold">
                                        <td colspan="3" class="text-end"><?php echo htmlspecialchars($current_class); ?> Total:</td>
                                        <td class="text-end"><?php echo formatMoney($class_initial_balance); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_initial_debit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_initial_credit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_movement_debit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_movement_credit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_closing_balance); ?></td>
                                        <td class="text-end"><?php echo formatMoney($class_closing_balance); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <!-- GRAND TOTALS ROW -->
                                    <tr class="grand-total-row">
                                        <td colspan="3" class="text-end">GRAND TOTAL:</td>
                                        <td class="text-end"><?php echo formatMoney($grand_initial_balance); ?></td>
                                        <td class="text-end"><?php echo formatMoney($grand_initial_debit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($grand_initial_credit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($grand_movement_debit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($grand_movement_credit); ?></td>
                                        <td class="text-end"><?php echo formatMoney($grand_closing_balance); ?></td>
                                        <td class="text-end"><?php echo formatMoney($grand_closing_balance); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php else: ?>
                        <!-- INCOME STATEMENT TABLE -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-sm table-hover" id="reportTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Group</th>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $current_class = '';
                                    $class_total = 0;
                                    
                                    foreach ($report_data as $row): 
                                        $closing_balance = $row['closing_balance'];
                                        $first_digit = substr($row['account_code'], 0, 1);
                                        
                                        // Display amount based on account type
                                        if ($first_digit == '4') {
                                            $display_amount = abs($closing_balance); // Revenue (show as positive)
                                        } else {
                                            $display_amount = $closing_balance; // Expenses (show as positive)
                                        }
                                        
                                        // Display class header when class changes
                                        if ($row['class'] != $current_class):
                                            // Print class total if not first class
                                            if ($current_class != ''):
                                    ?>
                                    <tr class="table-secondary fw-bold">
                                        <td colspan="3" class="text-end"><?php echo htmlspecialchars($current_class); ?> Total:</td>
                                        <td class="text-end"><?php echo formatMoney($class_total); ?></td>
                                    </tr>
                                    <?php 
                                                $class_total = 0;
                                            endif;
                                            $current_class = $row['class'];
                                        endif;
                                        
                                        $class_total += $display_amount;
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['class']); ?></td>
                                        <td>
                                            <span class="account-code"><?php echo htmlspecialchars($row['account_code']); ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                                        <td class="text-end amount-cell"><?php echo formatMoney($display_amount); ?></td>
                                    </tr>
                                    <?php endforeach; 
                                    
                                    // Print final class total
                                    if ($current_class != ''):
                                    ?>
                                    <tr class="table-secondary fw-bold">
                                        <td colspan="3" class="text-end"><?php echo htmlspecialchars($current_class); ?> Total:</td>
                                        <td class="text-end"><?php echo formatMoney($class_total); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Report Specific Summaries -->
                        <?php if ($report_type == 'balance_sheet'): ?>
                        <div class="financial-summary mt-4">
                            <h6><i class="fas fa-balance-scale me-2"></i>Balance Sheet Summary</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h6 class="card-title text-muted">Total Assets</h6>
                                            <p class="card-text h5 <?php echo $total_assets > 0 ? 'text-success' : 'text-danger'; ?>">
                                                <?php echo formatMoney($total_assets); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h6 class="card-title text-muted">Total Liabilities</h6>
                                            <p class="card-text h5 text-danger">
                                                <?php echo formatMoney(abs($total_liabilities)); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h6 class="card-title text-muted">Total Equity</h6>
                                            <p class="card-text h5 text-primary">
                                                <?php echo formatMoney(abs($total_equity)); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accounting-equation mt-3">
                                <h6><i class="fas fa-equals me-2"></i>Accounting Equation</h6>
                                <div class="text-center">
                                    <h5>
                                        Assets = Liabilities + Equity
                                    </h5>
                                    <p class="mb-0">
                                        <?php echo formatMoney($total_assets); ?> = 
                                        <?php echo formatMoney(abs($total_liabilities)); ?> + 
                                        <?php echo formatMoney(abs($total_equity)); ?>
                                    </p>
                                    <small class="text-muted">
                                        (Equity includes Net Income: <?php echo formatMoney($net_income_from_trial); ?>)
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <?php elseif ($report_type == 'income_statement'): ?>
                        <div class="financial-summary mt-4">
                            <h6><i class="fas fa-chart-line me-2"></i>Income Statement Summary</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h6 class="card-title text-muted">Total Revenue</h6>
                                            <p class="card-text h5 text-success">
                                                <?php echo formatMoney($total_revenue); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h6 class="card-title text-muted">Total Expenses</h6>
                                            <p class="card-text h5 text-danger">
                                                <?php echo formatMoney($total_expenses); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accounting-equation mt-3 <?php echo $net_income >= 0 ? 'bg-success' : 'bg-danger'; ?> text-white">
                                <div class="text-center">
                                    <h5>
                                        <i class="fas <?php echo $net_income >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'; ?> me-2"></i>
                                        Net <?php echo $net_income >= 0 ? 'Income (Profit)' : 'Loss'; ?>
                                    </h5>
                                    <h3 class="mb-0">
                                        <?php echo formatMoney(abs($net_income)); ?>
                                    </h3>
                                    <small>
                                        (<?php echo $total_revenue > 0 ? number_format(($net_income / $total_revenue) * 100, 2) : '0.00'; ?>% Profit Margin)
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-muted text-center">
                        Generated on <?php echo date('F d, Y h:i A'); ?> | 
                        <?php if ($report_type == 'balance_sheet'): ?>
                        As of: <?php echo date('d/m/Y', strtotime($end_date)); ?>
                        <?php else: ?>
                        Period: <?php echo date('d/m/Y', strtotime($start_date)); ?> - <?php echo date('d/m/Y', strtotime($end_date)); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function switchReportType(type) {
        const form = document.getElementById('reportFilter');
        const typeInput = form.querySelector('input[name="type"]');
        if (typeInput) {
            typeInput.value = type;
        } else {
            const newInput = document.createElement('input');
            newInput.type = 'hidden';
            newInput.name = 'type';
            newInput.value = type;
            form.appendChild(newInput);
        }
        form.submit();
    }
    
    function setDateRange(range) {
        const today = new Date();
        const startDateInput = document.querySelector('input[name="start_date"]');
        const endDateInput = document.querySelector('input[name="end_date"]');
        
        if (range === 'today') {
            startDateInput.value = formatDate(today);
            endDateInput.value = formatDate(today);
        } else if (range === 'month') {
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            startDateInput.value = formatDate(firstDay);
            endDateInput.value = formatDate(today);
        } else if (range === 'year') {
            const firstDay = new Date(today.getFullYear(), 0, 1);
            startDateInput.value = formatDate(firstDay);
            endDateInput.value = formatDate(today);
        }
        
        document.getElementById('reportFilter').submit();
    }
    
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    function exportToExcel() {
        const table = document.getElementById('reportTable');
        const html = table.outerHTML;
        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = '<?php echo preg_replace("/[^a-zA-Z0-9]/", "_", $report_title) . '_' . date('Y-m-d'); ?>.xls';
        a.click();
        URL.revokeObjectURL(url);
    }
    </script>
</body>
</html>

<?php 
// Close connections
if (isset($conn)) mysqli_close($conn); 
?>