<?php

namespace Botble\RealEstate\Http\Controllers\Fronts;

use App\Http\Controllers\Controller;
use Botble\Base\Facades\AssetsFacade;
use Botble\RealEstate\Models\Invoice;
use Botble\RealEstate\Repositories\Interfaces\InvoiceInterface;
use Botble\RealEstate\Supports\InvoiceHelper;
use Botble\RealEstate\Tables\AccountInvoiceTable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use OptimizerHelper;
use SeoHelper;
use Theme;

class InvoiceController extends Controller
{
    public function __construct(Repository $config, protected InvoiceInterface $invoiceRepository)
    {
        AssetsFacade::setConfig($config->get('plugins.real-estate.assets'));

        OptimizerHelper::disable();
    }

    public function index(AccountInvoiceTable $accountInvoiceTable)
    {
        page_title()->setTitle(__('Invoices'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('My Profile'), route('public.account.dashboard'))
            ->add(__('Manage Invoices'));

        SeoHelper::setTitle(__('Invoices'));

        return $accountInvoiceTable->render('plugins/real-estate::account.table.base');
    }

    public function show(int|string $id)
    {
        $invoice = $this->invoiceRepository->findOrFail($id);

        if (! $this->canViewInvoice($invoice)) {
            abort(404);
        }

        $title = __('Invoice detail :code', ['code' => $invoice->code]);

        page_title()->setTitle($title);

        SeoHelper::setTitle($title);

        return view('plugins/real-estate::account.dashboard.invoices.show', compact('invoice'));
    }

    public function generate(int|string $id, Request $request, InvoiceHelper $invoiceHelper)
    {
        $invoice = $this->invoiceRepository->findOrFail($id);

        if (! $this->canViewInvoice($invoice)) {
            abort(404);
        }

        if ($request->input('type') === 'print') {
            return $invoiceHelper->streamInvoice($invoice);
        }

        return $invoiceHelper->downloadInvoice($invoice);
    }

    protected function canViewInvoice(Invoice $invoice): bool
    {
        return auth('account')->id() == $invoice->payment->customer_id;
    }
}
