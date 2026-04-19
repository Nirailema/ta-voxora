<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DeviceController extends Controller
{
    public function index(): View
    {
        $devices = Device::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.devices.index', compact('devices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'endpoint_url' => ['required', 'url'],
        ]);

        Device::create([
            'name' => $validated['name'],
            'endpoint_url' => $validated['endpoint_url'],
            'status' => 'active',
        ]);

        return back()->with('success', 'Perangkat EduBraille berhasil ditambahkan.');
    }

    public function update(Request $request, Device $device): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'endpoint_url' => ['required', 'url'],
        ]);

        $device->update($validated);

        return back()->with('success', 'Perangkat berhasil diperbarui.');
    }

    public function destroy(Device $device): RedirectResponse
    {
        $device->delete();

        return redirect()->route('admin.devices.index')->with('success', 'Perangkat berhasil dihapus.');
    }

    public function toggleStatus(Device $device): RedirectResponse
    {
        $newStatus = $device->status === 'active' ? 'inactive' : 'active';
        $device->update(['status' => $newStatus]);

        return back()->with('success', 'Status perangkat berhasil diubah.');
    }
}
