<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\MediaLibraryRequest;
use App\Models\Media;
use App\Models\MediaLibrary;
use Illuminate\Http\Request;

class MediaLibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $media = Media::latest()->paginate(50);
        return response()->json($media);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (MediaLibraryRequest $request)
    {
        $image = $request->file('image');
        $name = $image->getClientOriginalName();
        
        MediaLibrary::latest()->first()->addMedia($image)
            ->usingName($name)
            ->toMediaCollection();

        $model = Media::latest()->first();

        return response()->json(data: ['message' => 'Изображение добавлено', 'imgUrl' => $model->original_url, 'id' => $model->id], status: 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $media = Media::findOrFail($id);
        $media->delete();
        return response('Изображение удалено', 204);
    }
}
