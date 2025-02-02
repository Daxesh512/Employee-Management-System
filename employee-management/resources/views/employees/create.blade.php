
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold">Add Employee</h1>
        <form id="employeeForm">
            @csrf
            <div class="mb-4">
                <label>Name</label>
                <input type="text" name="name" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label>Phone</label>
                <input type="text" name="phone" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label>Position</label>
                <input type="text" name="position" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label>Salary</label>
                <input type="number" name="salary" class="border p-2 w-full" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Employee</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#employeeForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('employees.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.success);
                    window.location.href = "{{ route('employees.index') }}";
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