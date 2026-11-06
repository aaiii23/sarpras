<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\Location;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);

        $complaints = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        // Statistics for current filter
        $statsQuery = $this->buildFilteredQuery($request);
        $filteredComplaints = $statsQuery->get();

        $stats = [
            'total' => $filteredComplaints->count(),
            'pending' => $filteredComplaints->where('status', 'pending')->count(),
            'in_progress' => $filteredComplaints->where('status', 'in_progress')->count(),
            'resolved' => $filteredComplaints->where('status', 'resolved')->count(),
            'rejected' => $filteredComplaints->where('status', 'rejected')->count(),
        ];

        return view('reports.index', compact('complaints', 'categories', 'locations', 'stats'));
    }

    public function print(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $complaints = $query->latest()->get();

        $stats = [
            'total' => $complaints->count(),
            'pending' => $complaints->where('status', 'pending')->count(),
            'in_progress' => $complaints->where('status', 'in_progress')->count(),
            'resolved' => $complaints->where('status', 'resolved')->count(),
            'rejected' => $complaints->where('status', 'rejected')->count(),
        ];

        $filterInfo = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'category' => $request->category_id ? Category::find($request->category_id)?->name : null,
            'location' => $request->location_id ? Location::find($request->location_id)?->name : null,
        ];

        return view('reports.print', compact('complaints', 'stats', 'filterInfo'));
    }

    public function exportCsv(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $complaints = $query->latest()->get();

        $filename = 'Laporan_Pengaduan_Sarpras_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($complaints) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Indonesian characters and Excel compatibility
            fputs($file, "\xEF\xBB\xBF");

            // CSV Header
            fputcsv($file, [
                'No.',
                'Nomor Tiket',
                'Tanggal Lapor',
                'Nama Pelapor',
                'NIS / NIP',
                'Kategori Sarana',
                'Lokasi / Ruangan',
                'Judul Aduan',
                'Tingkat Urgensi',
                'Status Penanganan',
                'Deskripsi Masalah',
            ]);

            $no = 1;
            foreach ($complaints as $c) {
                fputcsv($file, [
                    $no++,
                    $c->ticket_number,
                    $c->created_at->format('d/m/Y H:i'),
                    $c->user->name,
                    $c->user->nis ?? '-',
                    $c->category->name,
                    $c->location->name,
                    $c->title,
                    $c->urgency_label,
                    $c->status_label,
                    $c->description,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Complaint::with(['user', 'category', 'location']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        return $query;
    }
}
