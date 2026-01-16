<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $patients = Patient::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'birth_date' => 'required|date',
        ]);

        Patient::create($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Pasien berhasil ditambahkan.');
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'birth_date' => 'required|date',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        
        return redirect()->route('patients.index')
            ->with('success', 'Pasien berhasil dihapus.');
    }
}