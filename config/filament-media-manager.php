<?php

return [
    "disk" => env('MEDIA_MANAGER_DISK', 'public'),

    "model" => [
        "folder" => \Tobil476\FilamentMediaManager\Models\Folder::class,
        "media" => \Tobil476\FilamentMediaManager\Models\Media::class,
    ],

    "api" => [
        "active" => false,
        "middlewares" => [
            "api",
            "auth:sanctum"
        ],
        "prefix" => "api/media-manager",
        "resources" => [
            "folders" => \Tobil476\FilamentMediaManager\Http\Resources\FoldersResource::class,
            "folder" => \Tobil476\FilamentMediaManager\Http\Resources\FolderResource::class,
            "media" => \Tobil476\FilamentMediaManager\Http\Resources\MediaResource::class
        ]
    ],

    "user" => [
      'column_name' => 'name', // Change the value if your field in users table is different from "name"
    ],
];
