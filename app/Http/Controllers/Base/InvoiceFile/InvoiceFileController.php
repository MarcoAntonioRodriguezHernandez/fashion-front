<?php

namespace App\Http\Controllers\Base\InvoiceFile;

use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Base\InvoiceFile\PostRequest;
use App\Http\Requests\Base\InvoiceFile\PutRequest;
use App\Models\Base\InvoiceFile;
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InvoiceFileController extends GenericCrudProvider
{
    use HandlerFilesTrait;

    protected string $modelClass = InvoiceFile::class;

    protected string $indexView = 'base.invoice_file.index';
    protected string $showView = 'base.invoice_file.show';
    protected string $createView = 'base.invoice_file.create';
    protected string $editView = 'base.invoice_file.edit';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $invoiceFiles = InvoiceFile::all();

        return compact('invoiceFiles');
    }

    protected function pushEditView(Model $model)
    {
        $invoiceFiles = InvoiceFile::all();

        return compact('invoiceFiles');
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        if ($request->hasFile('file')) {
            $folder = 'app';
            $name = $request->file('file')->getClientOriginalName();
            $disk = 'do';
            $fileId=$this->upload($request->file('file'), $folder, $name, $disk);
            $validatedData['invoice_file'] = $fileId;
            $data = ['file' => $name];
        }

        return $data;
    }

    public function upload($file, $folder, $fileName)
    {
        $path = $file->storeAs($folder, $fileName, 'public');
        $fileModel = new InvoiceFile();
        $fileModel->name = $fileName;
        $fileModel->save();

        return $fileModel->id;
    }
}
