<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::withCount('complaints')->orderBy('name')->get();
        return view('master.locations.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:locations,name'],
            'building' => ['nullable', 'string', 'max:100'],
            'floor' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama lokasi/ruangan wajib diisi.',
            'name.unique' => 'Lokasi/ruangan ini sudah ada.',
        ]);

        Location::create($validated);

        return back()->with('success', 'Lokasi/ruangan baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:locations,name,' . $location->id],
            'building' => ['nullable', 'string', 'max:100'],
            'floor' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $location->update($validated);

        return back()->with('success', 'Data lokasi/ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $location = Location::withCount('complaints')->findOrFail($id);

        if ($location->complaints_count > 0) {
            return back()->with('error', "Lokasi '{$location->name}' tidak dapat dihapus karena memiliki {$location->complaints_count} data pengaduan terkait.");
        }

        $location->delete();
        return back()->with('success', 'Lokasi/ruangan berhasil dihapus.');
    }
}
