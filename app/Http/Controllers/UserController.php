<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('company')->get();
        return view('users.index', compact('users'));
    }


    public function create()
    {
        // Retrieve all companies
        $companies = Company::all();

        // Check if there are any companies; if not, redirect with a message
        if ($companies->isEmpty()) {
            return redirect()->route('companies.index')->with('error', 'Please add a company first.');
        }

        // Pass companies to the create view
        return view('users.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id', // Validate company_id
        ]);


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // If not system admin, assign company_id
        if ($request->role !== 'system admin') {
            $user->company_id = $request->company_id;
        }

        $user->password = bcrypt($request->password);
        $user->save();


        return redirect()->route('users.index')->with('success', 'User added successfully!');
    }

    public function edit($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);
        
        // Retrieve all companies to display in a dropdown (if needed)
        $companies = Company::all();

        // Return the edit view with the user and companies data
        return view('users.edit', compact('user', 'companies'));
    }

    public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'role' => 'required|string|max:255',
                'password' => 'nullable|string|min:8',
                'company_code' => 'required|string|max:255',
                'company_description' => 'required|string|max:255',
            ]);

            $user = User::findOrFail($id);

            // Update user details
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
            ]);

            // Update or create the associated company details
            if ($user->company_id) {
                // Update existing company
                $company = $user->company;
                $company->update([
                    'code' => $request->company_code,
                    'description' => $request->company_description,
                ]);
            } else {
                // Create a new company if the user doesn't have one yet
                $company = Company::create([
                    'code' => $request->company_code,
                    'description' => $request->company_description,
                ]);

                // Assign the new company to the user
                $user->update(['company_id' => $company->id]);
            }

            return redirect()->route('users.index')->with('success', 'User and company details updated successfully.');
        }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }


}
