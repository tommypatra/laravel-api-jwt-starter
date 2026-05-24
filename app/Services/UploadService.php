<?php

namespace App\Services;

use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService
{
    public function getData(Request $request)
    {
        $search = $request->search;
        $perPage = min((int) ($request->per_page ?? 10), 100);
        $filter_mime_type = $request->filter_mime_type;
        $filter_user_id = $request->filter_user_id;

        $query = Upload::query()
            ->leftJoin('users', 'users.id', '=', 'uploads.user_id')
            ->select('uploads.*')
            ->with('user')
            ->where('user_id', Auth::user()->id);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('original_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter status
        |--------------------------------------------------------------------------
        */
        if ($filter_mime_type) {
            $query->where(function ($q) use ($filter_mime_type) {
                $q->where('mime_type', $filter_mime_type);
            });
        }
        // if ($filter_user_id) {
        //     $query->where(function ($q) use ($filter_user_id) {
        //         $q->where('user_id', Auth::user()->id);
        //     });
        // }

        /*
        |--------------------------------------------------------------------------
        | return sorting and paging
        |--------------------------------------------------------------------------
        */
        return $query
            ->orderBy('users.name', 'asc')
            ->orderBy('uploads.id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById($id)
    {
        return Upload::with(['user'])->findOrFail($id);
    }

    public function findByUuid($uuid)
    {
        return Upload::with(['user'])->where('uuid', $uuid)->firstOrFail();
    }

    public function store(Request $request)
    {
        $file = $request->file('berkas');

        $storedPath = $file->storeAs(
            'uploads',
            Str::uuid().'.'.$file->extension(),
            'private'
        );

        return Upload::create([
            'user_id' => Auth::user()->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => basename($storedPath),
            'path' => $storedPath,
            'mime_type' => $file->getMimeType(),
            'extension' => $file->extension(),
            'size' => $file->getSize(),
        ])->load('user');
    }

    public function destroy(int $id)
    {
        $upload = Upload::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($upload->path && Storage::disk('private')->exists($upload->path)) {
            Storage::disk('private')->delete($upload->path);
        }

        return $upload->delete();
    }

    public function view(string $uuid)
    {
        $upload = $this->findByUuid($uuid);

        abort_unless(
            Storage::disk('private')->exists($upload->path),
            404
        );

        return response()->file(
            Storage::disk('private')->path($upload->path),
            [
                'Content-Type' => $upload->mime_type,
                'Content-Disposition' => 'inline; filename="'.$upload->original_name.'"',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }
}
