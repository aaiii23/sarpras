<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\ComplaintLog;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Complaint::with(['category', 'location'])
            ->where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('location', function ($locQ) use ($search) {
                      $locQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $complaints = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('complaints.index', compact('complaints', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('complaints.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        // Auto handle free-text category if provided
        if ($request->filled('category_name') && !$request->filled('category_id')) {
            $cat = Category::firstOrCreate(
                ['name' => trim($request->input('category_name'))],
                ['icon' => 'bi-tag', 'description' => 'Kategori sarana sekolah']
            );
            $request->merge(['category_id' => $cat->id]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'description' => ['required', 'string', 'min:10'],
            'photo_evidence' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'title.required' => 'Nama barang/fasilitas dan jenis kerusakan wajib diisi.',
            'category_id.required' => 'Pilih kategori sarana.',
            'category_id.exists' => 'Kategori sarana yang dipilih tidak valid.',
            'location_id.required' => 'Pilih lokasi/ruangan fasilitas yang rusak.',
            'location_id.exists' => 'Lokasi/ruangan yang dipilih tidak tersedia.',
            'description.required' => 'Deskripsi detail kerusakan fasilitas wajib diisi.',
            'description.min' => 'Deskripsi detail kerusakan minimal 10 karakter agar informasi jelas dan mudah ditindaklanjuti.',
            'photo_evidence.required' => 'Foto bukti kerusakan wajib dilampirkan sebelum mengirim laporan.',
            'photo_evidence.image' => 'Berkas bukti harus berupa gambar.',
            'photo_evidence.mimes' => 'Format foto bukti harus berupa JPG, PNG, atau WEBP.',
            'photo_evidence.max' => 'Ukuran foto maksimal 3MB.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo_evidence')) {
            $photoPath = $request->file('photo_evidence')->store('complaints', 'public');
        }

        $ticketNumber = Complaint::generateTicketNumber();

        $complaint = Complaint::create([
            'ticket_number' => $ticketNumber,
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'location_id' => $validated['location_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'urgency' => null, // Kondisi kerusakan divalidasi oleh Admin
            'photo_evidence' => $photoPath,
            'status' => 'pending',
        ]);

        // Create initial log
        ComplaintLog::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'status_from' => null,
            'status_to' => 'pending',
            'notes' => 'Laporan pengaduan sarana baru berhasil dibuat oleh pengguna dan menunggu validasi kondisi oleh Petugas Sarpras.',
        ]);

        return redirect()->route('complaints.show', $complaint->id)
            ->with('success', "Laporan pengaduan berhasil dikirim! Nomor tiket Anda: {$ticketNumber}. Kondisi kerusakan akan divalidasi oleh Petugas Sarpras.");
    }

    public function show($id)
    {
        $complaint = Complaint::with(['category', 'location', 'logs.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('complaints.show', compact('complaint'));
    }

}
