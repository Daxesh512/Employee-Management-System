<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $filters;

    // public function __construct($filters)
    // {
    //     $this->filters = $filters;
    // }

    public function collection()
    {
       
        $query = Employee::query();

      
        if (isset($this->filters['filter_name']) && $this->filters['filter_name'] != '') {
            $query->where('name', 'like', '%' . $this->filters['filter_name'] . '%');
        }

        if (isset($this->filters['filter_email']) && $this->filters['filter_email'] != '') {
            $query->where('email', 'like', '%' . $this->filters['filter_email'] . '%');
        }

        if (isset($this->filters['filter_position']) && $this->filters['filter_position'] != '') {
            $query->where('position', 'like', '%' . $this->filters['filter_position'] . '%');
        }

        if (isset($this->filters['min_salary']) && $this->filters['min_salary'] != '') {
            $query->where('salary', '>=', (int) $this->filters['min_salary']);
        }

        if (isset($this->filters['max_salary']) && $this->filters['max_salary'] != '') {
            $query->where('salary', '<=', (int) $this->filters['max_salary']);
        }

        return $query->get(['id', 'name', 'email', 'phone', 'position', 'salary', 'created_at']); // Specify the fields you want to export
    }

    // public function collection()
    // {'name', 'email', 'phone', 'position', 'salary'
    //     return Employee::select('id', 'name', 'email', 'phone', 'position', 'salary', 'created_at')->get();
    // }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Phone', 'Position', 'Salary', 'Created At'];
    }
}