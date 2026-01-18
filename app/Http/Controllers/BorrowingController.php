<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;
use App\Models\Borrowing;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Borrowing::with(['user', 'item'])->latest();

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $borrowings = $query->paginate(10);

        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::where('stock', '>', 0)->where('condition', 'good')->get();
        return view('borrowings.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBorrowingRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';

        Borrowing::create($data);

        return redirect()->route('borrowings.index')->with('success', 'Permintaan peminjaman berhasil dikirim. Menunggu persetujuan Admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin() && $borrowing->user_id !== auth()->id()) {
            abort(403);
        }
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        // Status updates are handled in show/update, no separate edit view needed for flow
        return redirect()->route('borrowings.show', $borrowing);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBorrowingRequest $request, Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validated();
        $newStatus = $validated['status'];
        $oldStatus = $borrowing->status;

        DB::transaction(function () use ($borrowing, $newStatus, $oldStatus) {

            // Handle Stock Logic
            if ($oldStatus === 'pending' && $newStatus === 'approved') {
                $item = $borrowing->item;
                if ($item->stock < $borrowing->quantity) {
                    throw new \Exception('Stok tidak mencukupi.');
                }
                $item->decrement('stock', $borrowing->quantity);
            }

            if ($oldStatus === 'approved' && $newStatus === 'returned') {
                $borrowing->item->increment('stock', $borrowing->quantity);
                $borrowing->return_date = now();
            }

            $borrowing->update(['status' => $newStatus]);
        });

        return redirect()->route('borrowings.show', $borrowing)->with('success', 'Status peminjaman diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin() && $borrowing->user_id !== auth()->id()) {
            abort(403);
        }

        if ($borrowing->status === 'approved') {
            return back()->with('error', 'Tidak dapat menghapus peminjaman yang sedang aktif.');
        }

        $borrowing->delete();

        return redirect()->route('borrowings.index')->with('success', 'Data peminjaman dihapus.');
    }

    public function exportPdf()
    {
        if (auth()->user()->isAdmin()) {
            $borrowings = \App\Models\Borrowing::with(['user', 'item'])->latest()->get();
        } else {
            $borrowings = auth()->user()->borrowings()->with(['item'])->latest()->get();
        }

        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('borrowings.pdf', compact('borrowings', 'settings'));
        return $pdf->download('laporan-peminjaman.pdf');
    }

    public function exportCsv()
    {
        if (auth()->user()->isAdmin()) {
            $borrowings = \App\Models\Borrowing::with(['user', 'item'])->latest()->get();
        } else {
            $borrowings = auth()->user()->borrowings()->with(['item'])->latest()->get();
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-peminjaman.csv"',
        ];

        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        $callback = function () use ($borrowings, $settings) {
            $file = fopen('php://output', 'w');

            // Header Info
            fputcsv($file, [strtoupper($settings['school_name'] ?? 'Sistem Inventaris')]);
            fputcsv($file, ['Laporan Peminjaman Barang']);
            fputcsv($file, ['Tahun Ajaran: ' . ($settings['academic_year'] ?? '-') . ' - Semester: ' . ($settings['semester'] ?? '-')]);
            fputcsv($file, ['Dicetak pada: ' . now()->format('d M Y H:i')]);
            fputcsv($file, []); // Blank line

            fputcsv($file, ['No', 'Peminjam', 'Barang', 'Jumlah', 'Tgl Pinjam', 'Tgl Kembali', 'Status']);

            foreach ($borrowings as $index => $borrowing) {
                fputcsv($file, [
                    $index + 1,
                    $borrowing->user->name,
                    $borrowing->item->name,
                    $borrowing->quantity,
                    $borrowing->borrow_date->format('Y-m-d'),
                    $borrowing->return_date ? $borrowing->return_date->format('Y-m-d') : '-',
                    ucfirst($borrowing->status),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
