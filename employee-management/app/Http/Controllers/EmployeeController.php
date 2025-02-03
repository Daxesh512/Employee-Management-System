<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {
        // Initialize query
        $query = Employee::query();

        // Check for filtering input and apply to query
        if ($request->has('filter_name') && $request->filter_name != '') {
            $query->where('name', 'like', '%' . $request->filter_name . '%');
        }
        
        if ($request->has('filter_email') && $request->filter_email != '') {
            $query->where('email', 'like', '%' . $request->filter_email . '%');
        }
        
        if ($request->has('filter_position') && $request->filter_position != '') {
            $query->where('position', 'like', '%' . $request->filter_position . '%');
        }

        if ($request->has('min_salary') && $request->min_salary != '') {
            $query->where('salary', '>=', (int) $request->min_salary);
        }

        if ($request->has('max_salary') && $request->max_salary != '') {
            $query->where('salary', '<=', (int) $request->max_salary);
        }

        // Get employees with pagination
        $employees = $query->paginate(10);

        // Return the view with the filtered employees
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|numeric|digits:10',
            'position' => 'required',
            'salary' => 'required|numeric|gt:0', 
        ], [
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 3 characters long.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email format.',
            'email.unique' => 'Email must be unique.',
            'phone.required' => 'Phone is required.',
            'phone.numeric' => 'Phone must be numeric.',
            'phone.digits' => 'Phone must be exactly 10 digits.',
            'position.required' => 'Position should not be empty.',
            'salary.required' => 'Salary is required.',
            'salary.numeric' => 'Salary must be numeric.',
            'salary.gt' => 'Salary must be greater than 0.', 
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        Employee::create($request->all());
        return response()->json(['success' => 'Employee created successfully.']);
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone' => 'required|numeric|digits:10',
            'position' => 'required',
            'salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $employee = Employee::findOrFail($id);
        $employee->update($request->all());
        return response()->json(['success' => 'Employee updated successfully.']);
    }

    public function destroy($id) {
        $employee = Employee::find($id);
        if ($employee) {
            $employee->delete();
            return response()->json(['success' => 'Employee deleted successfully!']);
        }
        return response()->json(['error' => 'Employee not found!'], 404);
    }
    public function show($id)
{
    $employee = Employee::findOrFail($id);
    return view('employees.show', compact('employee'));
}


public function exportCSV()
    {
        return Excel::download(new EmployeesExport, 'employees.csv');
    }
}