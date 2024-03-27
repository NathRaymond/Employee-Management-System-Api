<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function save_employees(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:employees,email',
                'phone' => 'required|string|max:255|unique:employees,phone',
                'address' => 'required|string|max:255',
                'gender' => 'required|string|max:255',
                'role' => 'required|string|in:manager,developer,design,scrum master',
                'status' => 'required|string|in:employed,fired'
            ]);

            if ($validator->fails()) {
                return API_Response(400, false, ['message' => 'Validation failed', 'errors' => $validator->errors()]);
            }

            $employee = Employee::create($request->all());

            return API_Response(200, true, ['message' => 'Employee added successfully']);
        } catch (\Exception $e) {
            $errorBag = ['message' => $e->getMessage()];
            return API_Response(500, false, ['message' => 'Error creating employee', 'error' => $errorBag]);
        }
    }


    public function list_employees($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            return API_Response(200, true, ['employee' => $employee]);
        } catch (\Exception $e) {
            $errorBag = ['message' => $e->getMessage()];
            return API_Response(500, false, ['message' => 'Error fetching employee details', 'error' => $errorBag]);
        }
    }

    public function update_employees(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'role' => 'string|in:manager,developer,design,scrum master',
                'status' => 'string|in:employed,fired'
            ]);

            if ($validator->fails()) {
                return API_Response(400, false, ['message' => 'Validation failed', 'errors' => $validator->errors()]);
            }

            $employee = Employee::findOrFail($id);
            $employee->update($request->all());

            return API_Response(200, true, ['message' => 'Employee updated successfully']);
        } catch (\Exception $e) {
            $errorBag = ['message' => $e->getMessage()];
            return API_Response(500, false, ['message' => 'Error updating employee', 'error' => $errorBag]);
        }
    }
    public function delete_employees($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();

            return API_Response(200, true, ['message' => 'Employee deleted successfully']);
        } catch (\Exception $e) {
            $errorBag = ['message' => $e->getMessage()];
            return API_Response(500, false, ['message' => 'Error deleting employee', 'error' => $errorBag]);
        }
    }

    public function assignRole(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role' => 'required|string|in:manager,developer,design,scrum master'
            ]);

            if ($validator->fails()) {
                return API_Response(400, false, ['message' => 'Validation failed', 'errors' => $validator->errors()]);
            }

            $employee = Employee::findOrFail($id);
            $employee->update(['role' => $request->input('role')]);

            return API_Response(200, true, ['message' => 'Role assigned successfully']);
        } catch (\Exception $e) {
            $errorBag = ['message' => $e->getMessage()];
            return API_Response(500, false, ['message' => 'Error assigning role to employee', 'error' => $errorBag]);
        }
    }

    public function search_employees(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'id' => 'integer|exists:employees,id',
            ]);

            if ($validator->fails()) {
                return API_Response(400, false, ['message' => 'Validation failed', 'errors' => $validator->errors()]);
            }

            $name = $request->input('name');
            $id = $request->input('id');

            if (!$name && !$id) {
                return API_Response(400, false, ['message' => 'Please provide either name or ID']);
            }

            $query = Employee::query();

            if ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            }

            if ($id) {
                $query->orWhere('id', $id);
            }

            $employees = $query->get();

            return API_Response(200, true, ['employees' => $employees]);
        } catch (\Exception $e) {
            $errorBag = ['message' => $e->getMessage()];
            return API_Response(500, false, ['message' => 'Error searching for employees', 'error' => $errorBag]);
        }
    }
}
