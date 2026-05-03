<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * GET /api/packages?service_id=
     */
    public function index(Request $request)
    {
        $serviceId = $request->query('service_id');

        if (! $serviceId) {
            return response()->json(['message' => 'service_id ?????.'], 422);
        }

        $packages = Package::with('activeOptions')
            ->where('is_active', true)
            ->where('service_id', (int) $serviceId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json($packages);
    }
}
