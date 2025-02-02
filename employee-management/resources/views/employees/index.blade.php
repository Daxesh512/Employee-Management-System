<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Management</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <div class="container mx-auto mt-5">
            <h1 class="text-2xl font-bold">Employee List<br><br></h1>
            <div class="mb-4">
                <a href="{{ route('employees.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Employee</a>
                <a href="{{ route('employees.export') }}" class="bg-green-500 text-white px-4 py-2 rounded ml-2">Export</a>
            </div>
            <div class="mb-4">
                <form method="GET" action="{{ route('employees.index') }}" class="flex">
                    <input type="text" name="filter_name" placeholder="Filter by Name" class="border p-2 w-full mr-2">
                    <input type="email" name="filter_email" placeholder="Filter by Email" class="border p-2 w-full mr-2">
                    <input type="text" name="filter_position" placeholder="Filter by Position" class="border p-2 w-full mr-2">
                    <input type="number" name="min_salary" placeholder="Min Salary" class="border p-2 w-full mr-2">
        <input type="number" name="max_salary" placeholder="Max Salary" class="border p-2 w-full mr-2">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route('employees.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Reset</a>
    </form>
</div>
            <table class="min-w-full mt-4 border">
                <thead>
                    <tr>
                        <th class="border">Name</th>
                        <th class="border">Email</th>
                        <th class="border">Phone</th>
                        <th class="border">Position</th>
                        <th class="border">Salary</th>
                        <th class="border">Actions</th>
                    </tr>
                </thead>
                <tbody id="employeeTable">
                    @foreach ($employees as $employee)
                        <tr>
                            <td class="border">{{ $employee->name }}</td>
                            <td class="border">{{ $employee->email }}</td>
                            <td class="border">{{ $employee->phone }}</td>
                            <td class="border">{{ $employee->position }}</td>
                            <td class="border">{{ $employee->salary }}</td>
                            <td class="border">
                                <button class="edit bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition duration-200" data-id="{{ $employee->id }}">
                                    Edit
                                </button>
                                <button class="delete bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200" data-id="{{ $employee->id }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="paginationLinks">
                {{ $employees->links() }} 
            </div>
        </div>

        
        <div id="editModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-5 rounded shadow-lg">
                <h2 class="text-xl font-bold mb-4">Edit Employee</h2>
                <form id="editEmployeeForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editEmployeeId">
                    <div class="mb-4">
                        <label>Name</label>
                        <input type="text" name="name" id="editEmployeeName" class="border p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label>Email</label>
                        <input type="email" name="email" id="editEmployeeEmail" class="border p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label>Phone</label>
                        <input type="text" name="phone" id="editEmployeePhone" class="border p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label>Position</label>
                        <input type="text" name="position" id="editEmployeePosition" class="border p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label>Salary</label>
                        <input type="number" name="salary" id="editEmployeeSalary" class="border p-2 w-full" required>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Employee</button>
                    <button type="button" id="closeEditModal" class="bg-red-500 text-white px-4 py-2 rounded">Cancel</button>
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#filterForm').on('change keyup', function() {
                    $.ajax({
                        url: "{{ route('employees.index') }}",
                        type: "GET",
                        data: {
                            position: $('#position').val(),
                            salary_min: $('#salary_min').val(),
                            salary_max: $('#salary_max').val(),
                            name: $('#name').val()
                        },
                        success: function(data) {
                            $('#employeeTable').html(data);
                        }
                    });
                });
            });
            </script>

        <script>
            $(document).ready(function() {
                $(document).on('click', '.edit', function() {
                    let id = $(this).data('id');
                    $.get(`/employees/${id}/edit`, function(data) {
                        $('#editEmployeeId').val(data.id);
                        $('#editEmployeeName').val(data.name);
                        $('#editEmployeeEmail').val(data.email);
                        $('#editEmployeePhone').val(data.phone);
                        $('#editEmployeePosition').val(data.position);
                        $('#editEmployeeSalary').val(data.salary);
                        $('#editModal').removeClass('hidden');
                    }).fail(function() {
                        alert('Error fetching employee data.');
                    });
                });

                $(document).on('click', '.delete', function() {
                    let id = $(this).data('id');
                    if (confirm('Are you sure you want to delete this employee?')) {
                        $.ajax({
                            url: `/employees/${id}`,
                            type: 'DELETE',
                            data: {
                                "_token": $("meta[name='csrf-token']").attr("content")
                            },
                            success: function(response) {
                                alert(response.success);
                                location.reload();
                            },
                            error: function(xhr) {
                                alert('Error deleting employee: ' + xhr.responseText);
                            }
                        });
                    }
                });

                $('#editEmployeeForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#editEmployeeId').val();
                    $.ajax({
                        url: `/employees/${id}`,
                        method: "PUT",
                        data: $(this).serialize(),
                        success: function(response) {
                            alert(response.success);
                            location.reload();
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                            alert(Object.values(errors).flat().join('\n'));
                        }
                    });
                });

                $('#closeEditModal').on('click', function() {
                    $('#editModal').addClass('hidden');
                });
            });
        </script>
    </body>
    </html>
</x-app-layout>