<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookingPhotosController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'photos'     => 'required|array',
            'photos.*'   => 'image|max:20480', // 20 MB بالكيلوبايت
        ]);
        $booking = Booking::findOrFail($request->booking_id);
        $paths = [];
        foreach ($request->file('photos') as $file) {
            $path = $file->store('booking-photos/' . $booking->id, 'public');
            $paths[] = 'storage/' . $path;
        }
        $sort = $booking->photos()->max('sort_order') ?? 0;
        foreach ($paths as $p) {
            $booking->photos()->create(['path' => $p, 'sort_order' => ++$sort]);
        }
        return back()->with('message', 'تم رفع ' . count($paths) . ' صورة.');
    }

    public function destroy(BookingPhoto $photo)
    {
        $photo->clientSelections()->delete();
        if (str_starts_with($photo->path, 'storage/')) {
            $rel = str_replace('storage/', '', $photo->path);
            if (Storage::disk('public')->exists($rel)) {
                Storage::disk('public')->delete($rel);
            }
        }
        $photo->delete();
        return back()->with('message', 'تم حذف الصورة.');
    }
}
