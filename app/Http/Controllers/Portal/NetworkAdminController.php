<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\AlumniNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NetworkAdminController extends Controller
{
    /**
     * Normalize URLs so "facebook.com" or "www.facebook.com" becomes "https://facebook.com".
     */
    private function normalizeUrl(string $url): string
    {
        $url = trim($url);

        // If user typed "www.facebook.com" or "facebook.com/..", add https://
        if (!preg_match('~^https?://~i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        return $url;
    }

    /**
     * Validate URL after normalization (returns null if invalid).
     */
    private function isValidUrl(string $url): bool
    {
        return (bool) filter_var($url, FILTER_VALIDATE_URL);
    }

    public function index()
    {
        $networks = AlumniNetwork::with(['createdBy', 'updatedBy'])
            ->orderByRaw('sort_order IS NULL, sort_order ASC')
            ->orderBy('title')
            ->paginate(15);

        return view('networks.manage.index', compact('networks'));
    }

    public function create()
    {
        return view('networks.manage.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'link'        => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'logo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
            'is_active'   => ['nullable', 'boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0', 'max:1000000'],
        ]);

        $link = $this->normalizeUrl($data['link']);

        if (!$this->isValidUrl($link)) {
            return back()
                ->withErrors(['link' => 'Please enter a valid URL (example: https://facebook.com/...).'])
                ->withInput();
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('networks', 'public');
        }

        AlumniNetwork::create([
            'title'       => $data['title'],
            'link'        => $link,
            'description' => $data['description'] ?? null,
            'logo_path'   => $logoPath,
            'is_active'   => (bool)($data['is_active'] ?? true),
            'sort_order'  => $data['sort_order'] ?? null,
            'created_by'  => Auth::id(),
            'updated_by'  => Auth::id(),
        ]);

        return redirect()->route('portal.networks.manage.index')
            ->with('success', 'Network added successfully.');
    }

    public function edit(AlumniNetwork $network)
    {
        return view('networks.manage.edit', compact('network'));
    }

    public function update(Request $request, AlumniNetwork $network)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'link'        => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'logo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_logo' => ['nullable', 'boolean'],
            'is_active'   => ['nullable', 'boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0', 'max:1000000'],
        ]);

        $link = $this->normalizeUrl($data['link']);

        if (!$this->isValidUrl($link)) {
            return back()
                ->withErrors(['link' => 'Please enter a valid URL (example: https://facebook.com/...).'])
                ->withInput();
        }

        // Remove logo if requested
        if (!empty($data['remove_logo']) && $network->logo_path) {
            Storage::disk('public')->delete($network->logo_path);
            $network->logo_path = null;
        }

        // Replace logo if uploaded
        if ($request->hasFile('logo')) {
            if ($network->logo_path) {
                Storage::disk('public')->delete($network->logo_path);
            }
            $network->logo_path = $request->file('logo')->store('networks', 'public');
        }

        $network->title       = $data['title'];
        $network->link        = $link;
        $network->description = $data['description'] ?? null;
        $network->is_active   = (bool)($data['is_active'] ?? false);
        $network->sort_order  = $data['sort_order'] ?? null;
        $network->updated_by  = Auth::id();
        $network->save();

        return redirect()->route('portal.networks.manage.index')
            ->with('success', 'Network updated successfully.');
    }

    public function destroy(AlumniNetwork $network)
    {
        if ($network->logo_path) {
            Storage::disk('public')->delete($network->logo_path);
        }

        $network->delete();

        return redirect()->route('portal.networks.manage.index')
            ->with('success', 'Network deleted successfully.');
    }

    public function toggle(AlumniNetwork $network)
    {
        $network->is_active = !$network->is_active;
        $network->updated_by = Auth::id();
        $network->save();

        return back()->with('success', 'Visibility updated.');
    }
}
