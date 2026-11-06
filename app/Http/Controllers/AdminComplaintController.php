<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\ComplaintLog;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'category', 'location']);

        // Workflow Tabs / Status Filters
        $currentTab = $request->get('tab', $request->get('status', 'all'));

        if ($currentTab === 'unvalidated' || $currentTab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($currentTab === 'in_progress') {
            $query->where('status', 'in_progress')
                  ->orderByRaw("CASE 
                    WHEN urgency = 'darurat' THEN 1 
                    WHEN urgency = 'tinggi' THEN 2 
                    WHEN urgency = 'sedang' THEN 3 
                    WHEN urgency = 'rendah' THEN 4 
                    ELSE 5 END ASC");
        } elseif ($currentTab === 'resolved') {
            $query->where('status', 'resolved');
        } elseif ($currentTab === 'rejected') {
            $query->where('status', 'rejected');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        // Global search covering title, ticket, description, user, AND location/room
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%");
                  })
                  ->orWhereHas('location', function ($locQ) use ($search) {
                      $locQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $complaints = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        // Status counts for separated workflow tabs
        $counts = [
            'all' => Complaint::count(),
            'unvalidated' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'rejected' => Complaint::where('status', 'rejected')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'categories', 'counts', 'currentTab'));
    }

    public function show($id)
    {
        $complaint = Complaint::with(['user', 'category', 'location', 'logs.user'])
            ->findOrFail($id);

        return view('admin.complaints.show', compact('complaint'));
    }

    public function updateStatus(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,resolved,rejected'],
            'urgency' => ['nullable', 'in:rendah,sedang,tinggi,darurat'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'photo_resolved' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'status.required' => 'Pilih status penanganan tiket.',
            'photo_resolved.image' => 'Bukti perbaikan harus berupa gambar.',
            'photo_resolved.max' => 'Ukuran foto maksimal 3MB.',
        ]);

        $photoResolvedPath = null;
        if ($request->hasFile('photo_resolved')) {
            $photoResolvedPath = $request->file('photo_resolved')->store('resolved', 'public');
        }

        $oldStatus = $complaint->status;
        $newStatus = $validated['status'];

        $updateData = [
            'status' => $newStatus,
        ];

        if ($request->filled('urgency')) {
            $updateData['urgency'] = $validated['urgency'];
        }

        $complaint->update($updateData);

        // Optional notes with clean default fallback
        $defaultNote = match ($newStatus) {
            'in_progress' => 'Laporan telah diverifikasi oleh Petugas Sarpras dan sedang dalam proses pengerjaan teknis.',
            'resolved' => 'Perbaikan fasilitas sarana sekolah telah tuntas dikerjakan.',
            'rejected' => 'Laporan pengaduan tidak dapat diproses / ditolak oleh Admin.',
            'pending' => 'Status laporan dikembalikan ke antrean belum diverifikasi.',
            default => 'Pembaruan status laporan oleh Petugas Sarpras.',
        };

        $userNote = !empty(trim($validated['notes'] ?? '')) ? trim($validated['notes']) : $defaultNote;

        // Append condition text if urgency is present
        $urgencyText = '';
        if ($request->filled('urgency')) {
            $urgencyText = " [Kondisi: " . $complaint->urgency_label . "]";
        }

        ComplaintLog::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'status_from' => $oldStatus,
            'status_to' => $newStatus,
            'notes' => $userNote . $urgencyText,
            'photo_resolved' => $photoResolvedPath,
        ]);

        return redirect()->route('admin.complaints.index')
            ->with('success', "Tiket {$complaint->ticket_number} berhasil diperbarui (Status: {$complaint->status_label}, Kondisi: {$complaint->urgency_label}).");
    }

    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        
        if ($complaint->photo_evidence) {
            Storage::disk('public')->delete($complaint->photo_evidence);
        }

        foreach ($complaint->logs as $log) {
            if ($log->photo_resolved) {
                Storage::disk('public')->delete($log->photo_resolved);
            }
        }

        $ticket = $complaint->ticket_number;
        $complaint->delete();

        return redirect()->route('admin.complaints.index')
            ->with('success', "Tiket aduan {$ticket} berhasil dihapus dari sistem.");
    }
}
