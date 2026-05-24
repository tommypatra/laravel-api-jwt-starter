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

        $query = Upload::query()
            ->leftJoin('users', 'users.id', '=', 'uploads.user_id')
            ->select('uploads.*')
            ->with('user')
            ->where('uploads.user_id', Auth::id());

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('uploads.original_name', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        if ($filter_mime_type) {
            $query->where('uploads.mime_type', $filter_mime_type);
        }

        return $query
            ->orderBy('users.name', 'asc')
            ->orderBy('uploads.id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById($id)
    {
        return Upload::with('user')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    public function findByUuid($uuid)
    {
        return Upload::with(['user'])->where('uuid', $uuid)->firstOrFail();
    }

    public function store(Request $request)
    {
        $file = $request->file('berkas');
        $uuid = (string) Str::ulid();

        $storedPath = $file->storeAs(
            'uploads',
            $uuid.'.'.$file->extension(),
            'public'
        );

        return Upload::create([
            'user_id' => Auth::id(),
            'uuid' => $uuid,
            'original_name' => $file->getClientOriginalName(),
            'path' => $storedPath,
            'disk' => 'public',
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ])->load('user');
    }

    public function destroy(int $id)
    {
        $upload = Upload::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($upload->path && Storage::disk('public')->exists($upload->path)) {
            Storage::disk('public')->delete($upload->path);
        }

        return $upload->delete();
    }

    public function view(string $uuid)
    {
        $upload = $this->findByUuid($uuid);

        abort_unless(
            Storage::disk('public')->exists($upload->path),
            404
        );

        return response()->file(
            Storage::disk('public')->path($upload->path),
            [
                'Content-Type' => $upload->mime_type,
                'Content-Disposition' => 'inline; filename="'.$upload->original_name.'"',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }
}
