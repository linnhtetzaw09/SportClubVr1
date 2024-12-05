<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventUser;
use App\Models\Event; // If you need to work with events

class EventRegistrationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:12|max:100',
            'phone' => 'required|string|max:15',
            'event_id' => 'required|exists:events,id',
        ]);

        $registration = new EventUser();
        $registration->name = $request->name;
        $registration->age = $request->age;
        $registration->phone = $request->phone;
        $registration->event_id = $request->event_id;
        $registration->save();

        return response()->json([
            'success' => true,
            'message' => 'Registration successful!',
        ]);
    }

    public function showRegistrations()
    {
        $event_user = EventUser::with('event')->get(); // Fetch all users with related event
        return view('admininterface', compact('event_user'));
    }

    // Show the edit form for the specified registration
    public function edit($id)
{
    $event_user = EventUser::findOrFail($id);
    return response()->json($event_user);
}

    // Update the specified registration
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:12|max:100',
            'phone' => 'required|string|max:15',
            'event_id' => 'required|exists:events,id',
        ]);

        $event_user = EventUser::findOrFail($id);
        $event_user->name = $request->name;
        $event_user->age = $request->age;
        $event_user->phone = $request->phone;
        $event_user->event_id = $request->event_id;
        $event_user->save();

        return redirect()->route('registrations.index')->with('success', 'Registration updated successfully!');
    }

    // Destroy (delete) the specified registration
    public function destroy($id)
    {
        $event_user = EventUser::findOrFail($id);
        $event_user->delete();

        return redirect()->route('registrations.index')->with('success', 'Registration deleted successfully!');
    }
}
