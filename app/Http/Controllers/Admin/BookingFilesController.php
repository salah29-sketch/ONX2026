<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookingFilesController extends Controller
{
    /**
     * رفع ملف جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id'  => 'required|exists:bookings,id',
            'label'       => 'required|string|max:255',
            'type'        => 'required|in:video,zip,pdf,other',
            'is_visible'  => 'nullable|boolean',
            'file'        => 'required|file|max:512000', // 500 MB
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $file    = $request->file('file');

        $path = $file->store('booking-files/' . $booking->id, 'public');

        BookingFile::create([
            'booking_id' => $booking->id,
            'label'      => $request->label,
            'path'       => 'storage/' . $path,
            'type'       => $request->type,
            'size'       => $file->getSize(),
            'is_visible' => $request->boolean('is_visible', true),
        ]);

        return back()->with('message', 'تم رفع الملف بنجاح.');
    }

    /**
     * تبديل الظهور للعميل
     */
    public function toggleVisibility(BookingFile $file)
    {
        $file->update(['is_visible' => !$file->is_visible]);
        return back()->with('message', $file->is_visible ? 'الملف مرئي الآن للعميل.' : 'تم إخفاء الملف عن العميل.');
    }

    /**
     * حذف ملف
     */
    public function destroy(BookingFile $file)
    {
        if (str_starts_with($file->path, 'storage/')) {
            $rel = str_replace('storage/', '', $file->path);
            if (Storage::disk('public')->exists($rel)) {
                Storage::disk('public')->delete($rel);
            }
        }
        $file->delete();
        return back()->with('message', 'تم حذف الملف.');
    }
}
