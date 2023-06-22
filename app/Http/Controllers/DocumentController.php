<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Models\Category;
use App\Support\InteractsWithBanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Throwable;

class DocumentController extends Controller
{
    use InteractsWithBanner;


    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreDocumentRequest  $request
     * @param  Category  $category
     *
     * @return RedirectResponse
     */
    public function store(StoreDocumentRequest $request, Category $category): RedirectResponse
    {
        if (Gate::denies('authorize_upload_to_category', $category)) {
            $this->banner('You have no permission to upload to this category.', 'danger');
            return redirect()->route('dashboard');
        }

        $data = [
            'user_id' => auth()->user()->id,
            'view_name' => $request->view_name,
            'category_id' => $request->category_id,
            'version' => '1.0',
        ];

        $file = $request->file('file_path');

        $success = Document::uploadFile($file, $data);
        if (!$success) {
            $this->banner('Error during document upload.', 'danger');
            return redirect()->route('dashboard');
        }

        Document::create($data);

        $this->banner(__('Document successfully uploaded.'));
        return redirect()->route('dashboard');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateDocumentRequest  $request
     * @param  Document  $document
     *
     * @return RedirectResponse
     */
    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $userId = intval(auth()->user()->id);
        $categoryId = intval($request->category_id);
        $category = Category::findOrFail($categoryId);

        if (Gate::denies('authorize_upload_to_category', $category)) {
            $this->banner('You have no permission to upload to this category.', 'danger');
            return redirect()->route('dashboard');
        }

        $data = [
            'user_id' => $userId,
            'view_name' => $request->view_name,
            'category_id' => $categoryId,
            'version' => '1.0',
        ];

        $file = $request->file('file_path');

        // check if new image needs to be uploaded
        if ($file) {
            $success = Document::uploadFile($file, $data);
            if (!$success) {
                $this->banner('Error during document upload.', 'danger');
                return redirect()->route('dashboard');
            }
        }

        $document->update($data);

        $this->banner(__('Successfully modified the document!'));

        return redirect()->route('dashboard');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Document  $document
     *
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Document $document): RedirectResponse
    {
        $oldName = htmlentities($document->view_name);
        $document->deleteOrFail();

        $this->banner('"'.$oldName.'"'.' successfully deleted!');
        return redirect()->route('dashboard');
    }
}
