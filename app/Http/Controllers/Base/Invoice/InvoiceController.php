<?php

namespace App\Http\Controllers\Base\Invoice;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\Invoice\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\{
    Invoice,
    InvoiceFile,
    PaymentType
};
use App\Models\User;
use App\Traits\Helpers\HandlerFilesTrait;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends GenericCrudProvider
{
    use HandlerFilesTrait;

    protected string $modelClass = Invoice::class;

    protected string $indexView = 'base.invoice.index';
    protected string $showView = 'base.invoice.show';
    protected string $createView = 'base.invoice.create';
    protected string $editView = 'base.invoice.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }
    protected function pushCreateView()
    {
        $paymentMethods = PaymentType::all();
        $invoiceFiles = InvoiceFile::all();
        $users = User::employees()->get();

        return compact('paymentMethods', 'invoiceFiles', 'users');
    }

    protected function pushEditView(Model $model)
    {
        $paymentMethods = PaymentType::all();
        $invoiceFiles = InvoiceFile::all();
        $users = User::employees()->get();

        return compact('paymentMethods', 'invoiceFiles', 'users');
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        if ($request->hasFile('invoice_file')) {
            $file = $request->file('invoice_file');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $invoiceFiles = InvoiceFile::create([
                'file' => $this->upload($file, 'invoices', 'public', $fileName),
            ]);

            $documentId = $invoiceFiles->id;
        }

        $data['buyer'] = $request->input('buyer_id');
        $data['invoice_file'] = $documentId;

        return $data;
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        parent::beforeUpdate($validatedData, $model, $request);

        $field = $request->id;

        if ($request->hasFile('file')) {
            $folder = 'app';
            $name = $request->file('file')->getClientOriginalName();
            $this->upload($request->file('file'), $folder, $name);

            if ($model->invoiceFile) {
                $this->deleteFile($model->invoiceFile->file);
            }

            $invoiceFile = $model->invoiceFile ?? new InvoiceFile();
            $invoiceFile->file = $name;
            $invoiceFile->save();

            $documentId = $invoiceFile->id;
            $validatedData['invoice_file'] = $documentId;

            $validatedData['file_name'] = $name;
        }

        return $validatedData;
    }


    public function getInvoiceByNumber($invoiceNumber)
    {
        try {
            $invoice = Invoice::with('invoiceFile')->where('invoice_number', $invoiceNumber)->firstOrFail();

            return response()->json($invoice);
        } catch (Exception $e) {
            return response()->json(['error' => 'Factura no encontrada'], 404);
        }
    }

    protected function beforeDelete(Model $model)
    {
        $invoiceFile = InvoiceFile::find($model->invoice_file);
        Storage::delete('public/app/' . $invoiceFile->file);
        $invoiceFile->delete();
    }

    public function deleteFile($filename)
    {
        Storage::delete('public/app/' . $filename);
    }
}
