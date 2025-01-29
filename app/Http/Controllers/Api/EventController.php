<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\EventCategory;

class EventController extends Controller
{
    public function index()
    {
        //ambil semua data event
        $events = Event::all();

        //load event_category dan vendor
        $events->load('eventCategory', 'vendor');

        return response()->json([
            'status' => 'success',
            'message' => 'Events fetched successfully',
            'data' => $events,
        ]);
    }

    //get all event categories
    public function categories()
    {
        //get all event categories
        $categories = EventCategory::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Event categories fetched successfully',
            'data' => $categories,
        ]);
    }

    //detail event and sku by event_id
    public function detail(Request $request)
    {
        //validasi request
        // $request->validate([
        //     'event_id' => 'required|exists:events,id'
        // ]);

        //event by event_id
        $event = Event::find($request->event_id);

        //jika event tidak ditemukan
        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        //load event_category and vendor
        $event->load('eventCategory', 'vendor');
        $skus = $event->skus;
        $event['skus'] = $skus;

        return response()->json([
            'status' => 'success',
            'message' => 'Event berhasil ditemukan',
            'data' => $event,
        ], 200);
    }
}
