# Filament Media Manager (Enhanced Fork)

A powerful media manager for [FilamentPHP](https://filamentphp.com), built on top of [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary/), enhanced to support:

- ✅ Multi-file upload  
- ✅ Secure file access with custom route  
- ✅ Use of `private` disk without publishing Spatie config  
- ✅ Organization-based multi-tenancy support  

> This is a fork of [tomatophp/filament-media-manager](https://github.com/tomatophp/filament-media-manager), extended with advanced features. Original credit goes to [TomatoPHP](https://github.com/tomatophp).

---

## 🚀 Installation

```bash
composer require tobil476/filament-media-manager
```

> Replace the original `tomatophp/filament-media-manager` if you were using it.

---

## 🛠️ Setup

### 1. Publish Spatie migrations

```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
php artisan migrate
```

### 2. Publish the Filament Media Manager config

```bash
php artisan vendor:publish --tag="filament-media-manager-config"
```

This will create a file at `config/filament-media-manager.php`.

### 3. Configure private disk (optional)

In `config/filesystems.php`:

```php
'private' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'visibility' => 'private',
],
```

In `config/filament-media-manager.php`, change:

```php
"disk" => env('MEDIA_MANAGER_DISK', 'private'), //default is public
```
or create a variable directly in your .env file

---

## 🔐 Secure Access to Media Files

In your `routes/web.php`:

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use TomatoPHP\FilamentMediaManager\Models\Media;
use TomatoPHP\FilamentMediaManager\Models\Folder;

Route::get('/secure-media/{media}', function (Media $media, Request $request) {
    if (!auth()->check()) {
        abort(403, 'Unauthorized');
    }

    $folder = $media->model_type === Folder::class
        ? Folder::find($media->model_id)
        : null;

    $user = auth()->user();

    if ($user->role === 'admin') {
        // Admin access
    } elseif ($folder && !$folder->users->contains($user)) {
        abort(403, 'Access denied');
    }

    if (!Storage::disk($media->disk)->exists($media->getPathRelativeToRoot())) {
        abort(404);
    }

    return Response::file(
        $media->getPath(),
        [
            'Content-Type' => $media->mime_type,
            'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
        ]
    );
})->middleware('auth')->name('secure.media');
```
This route setup is custom for my laravel webapp since I'm using multi tenancy, I added a 'role' column in my user model, but you could adapt it to use it without it or with another model (like user model for user role and admin model for admin role)

In your views (if you want to publish the views, but the feature is already added by default):

```blade
{{ route('secure.media', $media) }}
```

---

## 🏢 Multi-Tenant Support

If using Filament's tenancy:

```php
->tenant(\App\Models\Organization::class, slugAttribute: 'slug')
```

And in your `User` model:

```php
use TomatoPHP\FilamentMediaManager\Traits\InteractsWithMediaFolders;

class User extends Authenticatable
{
    use InteractsWithMediaFolders;
}
```

---

## 🖼️ Uploading Multiple Files

Multiple upload is enabled natively in this fork via `MediaManagerInput` and Blade customizations.

---

## 📸 Screenshots

| Folders | Folder Password | Media Grid |
|--------|-----------------|------------|
| ![Folders](https://raw.githubusercontent.com/tomatophp/filament-media-manager/master/arts/folders.png) | ![Password](https://raw.githubusercontent.com/tomatophp/filament-media-manager/master/arts/folder-password.png) | ![Media](https://raw.githubusercontent.com/tomatophp/filament-media-manager/master/arts/media.png) |

---

## 📦 Additional Features

- ✅ Auto-folder creation per model or collection  
- ✅ Folder sharing with multiple users  
- ✅ File custom properties: title & description  
- ✅ PDF preview & custom file types via JS  

---

## 🙌 Credits

- Original by [TomatoPHP](https://github.com/tomatophp)  
- File management by [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary/)  
- UI powered by [FilamentPHP](https://filamentphp.com)

---

## 📄 License

[MIT](LICENSE)