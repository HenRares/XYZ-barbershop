<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index() { return view('admin.services', ['services' => Service::orderBy('id')->get(), 'editing' => null]); }

    public function store(Request $request)
    {
        Service::create($this->validated($request));
        return back()->with('success', 'Layanan ditambahkan.');
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->validated($request));
        return back()->with('success', 'Layanan diperbarui.');
    }

    public function destroy(Service $service)
    {
        if ($service->bookings()->exists()) {
            $service->update(['status' => 'nonaktif']);
            return back()->with('success', 'Layanan sudah dipakai booking, jadi dinonaktifkan agar histori tetap utuh.');
        }
        $service->delete();
        return back()->with('success', 'Layanan dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'], 'description' => ['nullable', 'string', 'max:1000'],
            'duration' => ['required', 'integer', 'min:5', 'max:480'], 'price' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);
    }
}
