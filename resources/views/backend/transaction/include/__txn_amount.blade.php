<strong class="text-nowrap {{ $type !== 'subtract' && $type !== 'investment' && $type !==  'withdraw' && $type !==  'send_money' && $type !==  'bonus_refund' ? 'text-success': 'text-danger'}}">
    {{ ($type !== 'subtract' && $type !== 'investment' && $type !==  'withdraw' && $type !==  'send_money'&& $type !==  'bonus_refund' ? '+': '-' ).$amount.' '.$currency }}
</strong>
