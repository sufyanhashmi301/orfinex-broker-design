<strong
    class="text-nowrap {{ $type !== 'subtract' && $type !== 'investment' && $type !== 'withdraw' && $type !== 'send_money' && $type !== 'send_money_internal' && $type !== 'bonus_refund' && $type !== 'bonus_subtract' ? 'text-success' : 'text-danger' }}">
    {{ ($type !== 'subtract' && $type !== 'investment' && $type !== 'withdraw' && $type !== 'send_money' && $type !== 'send_money_internal' && $type !== 'bonus_refund' && $type !== 'bonus_subtract' ? '+' : '-') . $amount . ' ' . $currency }}
</strong>
