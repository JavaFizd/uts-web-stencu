<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Tampilkan riwayat pengeluaran kas operasional warkop.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        $query = Expense::with('user')->latest('expense_date')->latest('id');

        // Pencarian berdasarkan judul pengeluaran atau nomor nota
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('expense_number', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Filter Kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter Tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->paginate(15)->withQueryString();

        // Ringkasan Pengeluaran Kas
        $totalToday = Expense::whereDate('expense_date', $today)->sum('amount');
        $totalThisMonth = Expense::where('expense_date', '>=', $startOfMonth)->sum('amount');
        $totalAllTime = Expense::sum('amount');

        $categories = [
            'Operasional Harian',
            'Gas & Listrik',
            'Perlengkapan & Kemasan',
            'Bahan Baku Tambahan',
            'Kebersihan & Perawatan',
            'Lain-lain',
        ];

        return view('expenses.index', compact('expenses', 'totalToday', 'totalThisMonth', 'totalAllTime', 'categories'));
    }

    /**
     * Tampilkan form input pengeluaran kas baru.
     */
    public function create()
    {
        $categories = [
            'Operasional Harian',
            'Gas & Listrik',
            'Perlengkapan & Kemasan',
            'Bahan Baku Tambahan',
            'Kebersihan & Perawatan',
            'Lain-lain',
        ];

        return view('expenses.create', compact('categories'));
    }

    /**
     * Simpan pengeluaran kas baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ], [
            'title.required' => 'Nama/keperluan pengeluaran wajib diisi.',
            'category.required' => 'Kategori pengeluaran wajib dipilih.',
            'amount.required' => 'Nominal pengeluaran wajib diisi.',
            'amount.min' => 'Nominal pengeluaran minimal Rp 1.',
            'expense_date.required' => 'Tanggal pengeluaran wajib diisi.',
        ]);

        // Generate Expense Number yang rapi: EXP-YYYYMM-0001
        $prefix = 'EXP-' . Carbon::parse($validated['expense_date'])->format('Ym') . '-';
        $latestExpense = Expense::where('expense_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $nextSeq = 1;
        if ($latestExpense) {
            $lastNum = (int) substr($latestExpense->expense_number, -4);
            $nextSeq = $lastNum + 1;
        }

        $expenseNumber = $prefix . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);

        Expense::create([
            'expense_number' => $expenseNumber,
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'expense_date' => $validated['expense_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran kas sebesar Rp ' . number_format($validated['amount'], 0, ',', '.') . ' berhasil dicatat.');
    }

    /**
     * Hapus data pengeluaran (Owner only).
     */
    public function destroy(Expense $expense)
    {
        if (!Auth::user()->isOwner()) {
            abort(403, 'Hanya Owner yang dapat menghapus catatan pengeluaran.');
        }

        $nominal = number_format($expense->amount, 0, ',', '.');
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', "Catatan pengeluaran senilai Rp {$nominal} telah dihapus.");
    }
}
