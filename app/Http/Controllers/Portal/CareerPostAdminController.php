<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\CareerPost;
use App\Models\CareerPostAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CareerPostAdminController extends Controller
{
    public function index()
    {
        $posts = CareerPost::withCount('attachments')
            ->orderByDesc('id')
            ->paginate(15);

        return view('portal.careers_admin.index', compact('posts'));
    }

    public function create()
    {
        return view('portal.careers_admin.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data, $request) {
            $post = CareerPost::create([
                'title'           => $data['title'],
                'company'         => $data['company'] ?? null,
                'location'        => $data['location'] ?? null,
                'employment_type' => $data['employment_type'] ?? null,
                'summary'         => $data['summary'] ?? null,
                'description'     => $data['description'] ?? null,
                'how_to_apply'    => $data['how_to_apply'] ?? null,
                'apply_url'       => $data['apply_url'] ?? null,
                'apply_email'     => $data['apply_email'] ?? null,
                'start_date'      => $data['start_date'] ?? null,
                'end_date'        => $data['end_date'] ?? null,
                'is_published'    => $request->boolean('is_published', true),
                'created_by'      => Auth::id(),
            ]);

            $this->saveAttachments($post, $request);
        });

        return redirect()
            ->route('portal.careers.admin.index')
            ->with('success', 'Career post created.');
    }

    public function edit(CareerPost $post)
    {
        $post->load('attachments');
        return view('portal.careers_admin.edit', compact('post'));
    }

    public function update(Request $request, CareerPost $post)
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data, $request, $post) {
            $post->update([
                'title'           => $data['title'],
                'company'         => $data['company'] ?? null,
                'location'        => $data['location'] ?? null,
                'employment_type' => $data['employment_type'] ?? null,
                'summary'         => $data['summary'] ?? null,
                'description'     => $data['description'] ?? null,
                'how_to_apply'    => $data['how_to_apply'] ?? null,
                'apply_url'       => $data['apply_url'] ?? null,
                'apply_email'     => $data['apply_email'] ?? null,
                'start_date'      => $data['start_date'] ?? null,
                'end_date'        => $data['end_date'] ?? null,
                'is_published'    => $request->boolean('is_published', false),
            ]);

            // delete selected attachments
            $deleteIds = $request->input('delete_attachments', []);
            if (is_array($deleteIds) && count($deleteIds)) {
                $attachments = CareerPostAttachment::where('career_post_id', $post->id)
                    ->whereIn('id', $deleteIds)
                    ->get();

                foreach ($attachments as $att) {
                    if ($att->path && Storage::disk('public')->exists($att->path)) {
                        Storage::disk('public')->delete($att->path);
                    }
                    $att->delete();
                }
            }

            // add new files
            $this->saveAttachments($post, $request);
        });

        return redirect()
            ->route('portal.careers.admin.edit', $post)
            ->with('success', 'Career post updated.');
    }

    public function destroy(CareerPost $post)
    {
        DB::transaction(function () use ($post) {
            $post->load('attachments');

            foreach ($post->attachments as $att) {
                if ($att->path && Storage::disk('public')->exists($att->path)) {
                    Storage::disk('public')->delete($att->path);
                }
                $att->delete();
            }

            Storage::disk('public')->deleteDirectory("careers/{$post->id}");

            $post->delete();
        });

        return redirect()
            ->route('portal.careers.admin.index')
            ->with('success', 'Career post deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title'           => ['required','string','max:255'],
            'company'         => ['nullable','string','max:255'],
            'location'        => ['nullable','string','max:255'],
            'employment_type' => ['nullable','string','max:100'],
            'summary'         => ['nullable','string','max:300'],
            'description'     => ['nullable','string'],
            'how_to_apply'    => ['nullable','string'],
            'apply_url'       => ['nullable','string','max:255'],
            'apply_email'     => ['nullable','string','max:255'],
            'start_date'      => ['nullable','date'],
            'end_date'        => ['nullable','date','after_or_equal:start_date'],
            'is_published'    => ['nullable'],

            'attachments'     => ['nullable','array'],
            'attachments.*'   => ['file','max:10240','mimes:pdf,jpg,jpeg,png,webp'],

            'delete_attachments'   => ['nullable','array'],
            'delete_attachments.*' => ['integer'],
        ]);
    }

    private function saveAttachments(CareerPost $post, Request $request): void
    {
        if (!$request->hasFile('attachments')) return;

        $files = $request->file('attachments');
        $maxSort = (int) CareerPostAttachment::where('career_post_id', $post->id)->max('sort_order');
        $nextSort = $maxSort + 1;

        foreach ($files as $file) {
            if (!$file || !$file->isValid()) continue;

            $storedPath = $file->store("careers/{$post->id}", 'public');

            CareerPostAttachment::create([
                'career_post_id' => $post->id,
                'path'           => $storedPath,
                'original_name'  => $file->getClientOriginalName(),
                'mime_type'      => $file->getClientMimeType(),
                'size'           => $file->getSize(),
                'sort_order'     => $nextSort++,
            ]);
        }
    }
}
