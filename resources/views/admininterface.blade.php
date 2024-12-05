@extends('layouts.app')

@section('content')

<!-- Custom Styles for Modals -->
<style>
    .btn {
        width: 100px; 
        height: 40px;
    }
</style>

<div class="container mt-5 pt-5">
    <h1 class="mb-4 mt-5 text-center text-primary">Admin Interface</h1>
    
    <!-- Statistics Section -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text fs-4 fw-bold">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Events</h5>
                    <p class="card-text fs-4 fw-bold">{{ $totalEvents }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Event Form -->
    <div class="mt-5">
        <h2 class="text-secondary">New Event</h2>

        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="p-4 bg-light rounded shadow-sm">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Event Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="col-md-3">
                    <label for="event_date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="event_date" name="event_date" required>
                </div>
                <div class="col-md-3">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" class="form-control" id="time" name="time" required>
                </div>
                <div class="col-md-6">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>
                <div class="col-md-6">
                    <label for="sport" class="form-label">Sport</label>
                    <input type="text" class="form-control" id="sport" name="sport" required>
                </div>
                <div class="col-md-6">
                    <label for="age_group" class="form-label">Age Group</label>
                    <input type="text" class="form-control" id="age_group" name="age_group" required>
                </div>
                <div class="col-md-6">
                    <label for="image" class="form-label">Event Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3 w-100">Add Event</button>
        </form>
    </div>

    <!-- Existing Events Section -->
    <div class="mt-5">
        <h3 class="mt-5 text-secondary">Existing Events</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No.</th>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($events as $event)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $event->title }}</td>
                            <td class="align-middle">{{ $event->event_date }}</td>
                            <td class="align-middle">{{ $event->time }}</td>
                            <td class="align-middle">{{ $event->location }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning " data-id="{{ $event->id }}" onclick="editEvent(this)">Edit</button>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- User Management Section -->
    <div class="mt-5">
        <h2 class="text-secondary">User Management</h2>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Preferred Sport</th>
                        <th>Skill Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($users as $user)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">{{ $user->preferred_sport }}</td>
                            <td class="align-middle">{{ $user->skill_level }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-id="{{ $user->id }}" onclick="editUser(this)">Edit</button>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="userForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="preferred_sport" class="form-label">Preferred Sport</label>
                        <input type="text" class="form-control" id="preferred_sport" name="preferred_sport">
                    </div>
                    <div class="mb-3">
                        <label for="skill_level" class="form-label">Skill Level</label>
                        <input type="text" class="form-control" id="skill_level" name="skill_level">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
    </div>

    <!-- User Registration Section -->
<div class="mt-5">
    <h3 class="mt-5 text-secondary">User Registration</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Phone</th>
                    <th>Event ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($event_user as $eu)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $eu->name }}</td>
                        <td class="align-middle">{{ $eu->age }}</td>
                        <td class="align-middle">{{ $eu->phone }}</td>
                        <td class="align-middle">{{ $eu->event_id }}</td>
                        <td>
                            <a class="btn btn-sm btn-warning" data-id="{{ $eu->id }}" onclick="editRegisterUser(this)">Edit</a>
                            <form action="{{ route('registrations.destroy', $eu->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



</div>

<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="eventEditForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Event Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_event_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="edit_event_date" name="event_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_time" class="form-label">Time</label>
                        <input type="time" class="form-control" id="edit_time" name="time" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="edit_location" name="location" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_sport" class="form-label">Sport</label>
                        <input type="text" class="form-control" id="edit_sport" name="sport" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_age_group" class="form-label">Age Group</label>
                        <input type="text" class="form-control" id="edit_age_group" name="age_group" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Event Image</label>
                        <input type="file" class="form-control" id="edit_image" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit RegisterUser Modal -->
<div class="modal fade" id="editRegisterUser" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="eventRegisterForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" name="age" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_event_id" class="form-label">Event ID</label>
                        <input type="number" class="form-control" id="event_id" name="event_id" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>


function editEvent(button) {
    const eventId = button.getAttribute('data-id');

    fetch(`/admin/events/${eventId}/edit`)
        .then(response => response.json())
        .then(data => {
            // Populate the modal with the event data
            document.getElementById('edit_title').value = data.title;
            document.getElementById('edit_event_date').value = data.event_date;
            document.getElementById('edit_time').value = data.time;
            document.getElementById('edit_location').value = data.location;
            document.getElementById('edit_sport').value = data.sport;
            document.getElementById('edit_age_group').value = data.age_group;
            document.getElementById('edit_description').value = data.description;

            // Update the form action to the correct route for the event
            const form = document.getElementById('eventEditForm');
            form.action = `/admin/events/${eventId}`;

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('editEventModal'));
            modal.show();
        });
}

function editUser(button) {
        const userId = button.getAttribute('data-id');

        fetch(`/admin/users/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                // Populate form fields with user data
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
                document.getElementById('preferred_sport').value = data.preferred_sport || '';
                document.getElementById('skill_level').value = data.skill_level || '';

                // Set form action to the update URL
                const form = document.getElementById('userForm');
                form.action = `/admin/users/${data.id}`;

                // Show the modal
                $('#editUserModal').modal('show');
            });
}

function editRegisterUser(button) {
    const userId = button.getAttribute('data-id'); // Get the user ID from the button

    fetch(`/registrations/${userId}/edit`)
    .then(response => {
        if (!response.ok) {
            console.error('HTTP Error:', response.status, response.statusText);
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('name').value = data.name;
        document.getElementById('age').value = data.age;
        document.getElementById('phone').value = data.phone;
        document.getElementById('event_id').value = data.event_id;

        const form = document.getElementById('eventRegisterForm');
        form.action = `/registrations/${userId}`;

        const modal = new bootstrap.Modal(document.getElementById('editRegisterUser'));
        modal.show();
    })
    .catch(error => {
        console.error('Error fetching user data:', error);
    });

}


</script>

@endsection
