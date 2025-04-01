<?php

namespace Tobil476\FilamentMediaManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tobil476\FilamentMediaManager\Http\Resources\FolderResource;
use Tobil476\FilamentMediaManager\Http\Resources\FoldersResource;
use Tobil476\FilamentMediaManager\Http\Resources\MediaResource;
use Tobil476\FilamentMediaManager\Models\Folder;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        $folders = config('filament-media-manager.model.folder')::query();

        if ($request->has('search')) {
            $folders->where('name', 'like', '%'.$request->search.'%');
        }

        return response()->json([
            'data' => config('filament-media-manager.api.resources.folders')::collection($folders->paginate(10))
        ], 200);
    }

    public function show(int $id)
    {
        $folder = Folder::query()->findOrFail($id);

        return response()->json([
            'data' => config('filament-media-manager.api.resources.folder')::make($folder)
        ], 200);
    }
}
