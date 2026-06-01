<?php

namespace App\Http\Controllers;

use App\Models\NotificationCustom;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = NotificationCustom::where('user_id', $user->id);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('lu')) {
            $query->where('lu', $request->lu === 'true');
        }

        $notifications = $query->recent()->paginate(20)->withQueryString();

        return view('notifications.index', compact('notifications'));
    }

    public function marquerCommeLu(NotificationCustom $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->marquerCommeLu();

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function marquerToutesLues()
    {
        $user = auth()->user();
        NotificationCustom::where('user_id', $user->id)
            ->nonLu()
            ->update([
                'lu' => true,
                'lu_at' => now(),
            ]);

        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    }

    public function compterNonLues()
    {
        $user = auth()->user();
        $count = NotificationCustom::where('user_id', $user->id)->nonLu()->count();

        return response()->json(['count' => $count]);
    }
}
