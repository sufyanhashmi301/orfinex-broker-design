@extends('frontend::layouts.user')
@section('title')
    {{ __('Settings') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('Settings') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Margin 4x360') }}
            </li>
        </ul>
    </div>

    <div class="">
        <div id="zf_div_qb8X9umGNZKxR09T6sMfcRz5G7xgglPcjPYDGE6RZDc" class="w-full"></div>
        <script type="text/javascript">(function() {
            try{
                var f = document.createElement("iframe");
                f.src = 'https://forms.orfinex.com/orfinex/form/ApplicationforMargin4x360/formperma/qb8X9umGNZKxR09T6sMfcRz5G7xgglPcjPYDGE6RZDc?zf_rszfm=1';
                f.style.border="none";
                f.style.height="442px";
                f.style.width="100%";
                f.style.transition="all 0.5s ease";
                f.setAttribute("aria-label", 'Application\x20for\x20Margin\x204x360');
                
                var d = document.getElementById("zf_div_qb8X9umGNZKxR09T6sMfcRz5G7xgglPcjPYDGE6RZDc");
                d.appendChild(f);
                window.addEventListener('message', function (){
                    var evntData = event.data;
                    if( evntData && evntData.constructor == String ){
                        var zf_ifrm_data = evntData.split("|");
                        if ( zf_ifrm_data.length == 2 ) {
                            var zf_perma = zf_ifrm_data[0];
                            var zf_ifrm_ht_nw = ( parseInt(zf_ifrm_data[1], 10) + 15 ) + "px";
                            var iframe = document.getElementById("zf_div_qb8X9umGNZKxR09T6sMfcRz5G7xgglPcjPYDGE6RZDc").getElementsByTagName("iframe")[0];
                            if ( (iframe.src).indexOf('formperma') > 0 && (iframe.src).indexOf(zf_perma) > 0 ) {
                                var prevIframeHeight = iframe.style.height;
                                if ( prevIframeHeight != zf_ifrm_ht_nw ) {
                                    iframe.style.height = zf_ifrm_ht_nw;
                                }
                            }
                        }
                    }
                }, false);
            }catch(e){}
            })();
        </script>
    </div>

@endsection