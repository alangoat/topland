<li class="dropdown dropdown-extended dropdown-inbox">
    <a href="javascript:;" class="dropdown-toggle dropdown-header-name" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="icon-envelope-open"></i>
        <span class="badge badge-default"> {{ $payments->total() }} </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="external">
            <h3>{!! BaseHelper::clean(trans('plugins/payment::payment.new_msg_notice', ['count' => $payments->total()])) !!}</h3>
            <a href="{{ route('payment.index') }}">{{ trans('plugins/payment::payment.view_all') }}</a>
        </li>
        <li>
            <ul class="dropdown-menu-list scroller" style="height: {{ $payments->total() * 70 }}px;" data-handle-color="#637283">
                @foreach($payments as $payment)
                    <li>
                        <a href="{{ url('/admin/payments/transactions/'.$payment->id) }}">
                            <span class="photo">
                                <img src="https://cdn-icons-png.flaticon.com/512/2761/2761118.png" class="rounded-circle" alt="{{ $payment->charge_id }}">
                            </span>
                            <span class="subject"><span class="from"> {{ $payment->charge_id }} </span><span class="time">{{ BaseHelper::formatDate($payment->created_at, 'd-m-Y') }} </span></span>
                            <span class="message"> {{ intval($payment->amount) }} {{ $payment->currency }} </span>
                        </a>
                    </li>
                @endforeach

                @if ($payments->total() > 10)
                    <li class="text-center"><a href="{{ route('payment.index') }}">{{ trans('plugins/payment::payment.view_all') }}</a></li>
                @endif
            </ul>
        </li>
    </ul>
</li>
