<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold">Edit Employee</h1>
        <form id="editEmployeeForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editEmployeeId" value="{{ $employee->id }}">
            <div class="mb-4">
                <label>Name</label>
                <input type="text" name="name" id="editEmployeeName" class="border p-2 w-full" value="{{ $employee->name }}" required>
            </div>
            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" id="editEmployeeEmail" class="border p-2 w-full" value="{{ $employee->email }}" required>
            </div>
            <div class="mb-4">
                <label>Phone</label>
                <input type="text" name="phone" id="editEmployeePhone" class="border p-2 w-full" value="{{ $employee->phone }}" required>
            </div>
            <div class="mb-4">
                <label>Position</label>
                <input type="text" name="position" id="editEmployeePosition" class="border p-2 w-full" value="{{ $employee->position }}" required>
            </div>
            <div class="mb-4">
                <label>Salary</label>
                <input type="number" name="salary" id="editEmployeeSalary" class="border p-2 w-full" value="{{ $employee->salary }}" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Employee</button>
            <a href="{{ route('employees.index') }}" >Cancel</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#editEmployeeForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#editEmployeeId').val();
            $.ajax({
                url: `/employees/${id}`,
                method: "PUT",
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.success);
                    window.location.href = "{{ route('employees.index') }}"; // Redirect to the employee index page
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    alert(Object.values(errors).flat().join('\n'));
                }
            });
        });
    </script>
</body>
</html>