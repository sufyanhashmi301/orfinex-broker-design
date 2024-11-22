<strong class="{{ $type !== 'subtract' && $type !== 'investment' && $type !==  'withdraw' && $type !==  'send_money' ? 'text-success': 'text-danger'}}">
    {{ ($type !== 'subtract' && $type !== 'investment' && $type !==  'withdraw' && $type !==  'send_money' ? '+': '-' ).$final_amount.' '.$currency }}
</strong>
