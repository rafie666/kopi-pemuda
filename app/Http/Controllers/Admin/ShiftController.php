<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ShiftController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $shiftType = $request->query('shift', 'all'); // 'siang' or 'malam'
        
        $shifts = \App\Models\Shift::with('user')
            ->when($shiftType !== 'all', function ($query) use ($shiftType) {
                // Shift Pagi (stored as 'siang'): 10:00 - 20:00
                // Shift Siang (stored as 'malam'): 14:00 - 00:00
                if ($shiftType === 'siang') {
                    $query->whereTime('start_time', '>=', '10:00:00')
                          ->whereTime('start_time', '<', '14:00:00'); 
                } else {
                     $query->whereTime('start_time', '>=', '14:00:00');
                }
            })
            ->latest()
            ->get();
        
        $view = 'shifts';
        return view('admin.user.index', compact('shifts', 'shiftType', 'kasirs', 'view'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'category' => 'required|in:siang,malam',
        ]);

        $date = $request->date;
        // Pagi ('siang') = 10:00, Siang ('malam') = 14:00
        $startTime = $request->category == 'siang' ? '10:00:00' : '14:00:00';
        
        // Combine date and time
        $startDateTime = $date . ' ' . $startTime;

        \App\Models\Shift::create([
            'user_id' => $request->user_id,
            'start_time' => $startDateTime,
            'cash_start' => 0, // Default 0 for manually added shifts
            'category' => $request->category,
        ]);

        return redirect()->back()->with('success', 'Shift berhasil ditambahkan.');
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'category' => 'required|in:siang,malam',
        ]);

        $shift = \App\Models\Shift::findOrFail($id);
        
        $date = $request->date;
        $startTime = $request->category == 'siang' ? '10:00:00' : '14:00:00';
        $startDateTime = $date . ' ' . $startTime;

        $shift->update([
            'user_id' => $request->user_id,
            'start_time' => $startDateTime,
            'category' => $request->category,
        ]);

        return redirect()->back()->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $shift = \App\Models\Shift::findOrFail($id);
        $shift->delete();
        return redirect()->back()->with('success', 'Shift berhasil dihapus.');
    }
}
