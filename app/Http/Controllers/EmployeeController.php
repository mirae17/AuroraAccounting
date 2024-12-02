<?php

namespace App\Http\Controllers;


use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function index()
    {

        $user = Auth::user();

        if ($user->role === 'system admin') {
           
            $employees = Employee::with('company')->get();
            $companies = Company::all(); // Get all companies
        } else {
           
            $employees = Employee::with('company')->where('company_id', $user->company_id)->get();
            $companies = []; 
        }

        
        return view('employees.index', compact('employees', 'companies'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cEmpNo' => 'required|string|max:10',
            'cEmpName' => 'required|string|max:100',
            'company_id' => 'required|exists:companies,id', // Validate company
        ]);
    
        $employees = new Employee();
        $employees->cEmpNo = $request->cEmpNo;
        $employees->cEmpName = $request->cEmpName;
    
        // Assign company based on role
        $employees->company_id = Auth::user()->role === 'system admin'
            ? $request->company_id
            : Auth::user()->company_id;
    
        $employees->save();
    
        return redirect()->route('employees.index')
                         ->with('success', 'Employee added successfully.');
    }
    

    public function edit(Employee $employeeMethod)
    {
        return view('employees.edit', compact('employeeMethod'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cEmpNo' => 'required|string|max:10',
            'cEmpName' => 'required|string|max:100',
            'company_id' => 'required|exists:companies,id', // Validate company
        ]);
    
        $employees = Employee::findOrFail($id);
    
        $employees->cEmpNo = $request->cEmpNo;
        $employees->cEmpName = $request->cEmpName;
    
        // Assign company based on role
        if (Auth::user()->role === 'system admin') {
            $employees->company_id = $request->company_id;
        }
    
        $employees->save();
    
        return redirect()->route('employees.index')
                         ->with('success', 'Employee updated successfully.');
    }
    
    
  

    public function destroy($iPymtdPk)
    {
        $employees= Employee::findOrFail($iPymtdPk);
        $employees->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}