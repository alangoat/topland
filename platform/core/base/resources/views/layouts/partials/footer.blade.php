<div class="page-footer">
    <div class="page-footer-inner">
        <div class="row">
            <div class="col-md-6">
                {!! BaseHelper::clean(
                    trans('core/base::layouts.copyright', [
                        'year' => Carbon\Carbon::now()->format('Y'),
                        'company' => setting('admin_title'),
                    ]),
                ) !!}
            </div>
        </div>
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up-circle"></i>
    </div>
</div>
