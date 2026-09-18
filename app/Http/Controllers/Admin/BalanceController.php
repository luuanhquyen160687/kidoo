<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends BaseController
{
    const TYPE_LABELS = [
        'tuition_payment' => 'Thu học phí',
        'topup' => 'Nạp tiền',
        'billing_charge' => 'Phí dịch vụ',
        'ticket_charge' => 'Phí vé sự kiện',
        'payout' => 'Rút tiền',
        'adjustment' => 'Điều chỉnh',
        'refund' => 'Hoàn tiền',
    ];

    public function index(Request $request)
    {
        $school_id = $this->app['school']->id;

        $transactions = DB::table('school_transactions')
            ->where('school_id', $school_id)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        $data['balance'] = DB::table('schools')->where('id', $school_id)->value('balance') ?? 0;
        $data['transactions'] = $transactions;
        $data['type_labels'] = self::TYPE_LABELS;

        return view('admin.balance.index', $data);
    }
}
