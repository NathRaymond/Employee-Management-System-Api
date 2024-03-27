<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function totalEmployees()
    {
        try {
            $totalEmployees = Employee::count();
            return API_Response(200, true, ['total_employees' => $totalEmployees]);
        } catch (\Exception $e) {
            $errorBag = ['message' => $e->getMessage()];
            return API_Response(500, false, ['message' => 'Error fetching total employees', 'error' => $errorBag]);
        }
    }

    public function totalRoles()
    {
        try {
            $totalRoles = Role::count();
            return API_Response(200, true, ['total_roles' => $totalRoles]);
        } catch (\Exception $e) {
            $errorBag = ['message' => $e->getMessage()];
            return API_Response(500, false, ['message' => 'Error fetching total roles', 'error' => $errorBag]);
        }
    }

    public function createRole(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|unique:roles|max:255'
            ]);

            $role = Role::create($request->all());

            return API_Response(200, true, ['role' => $role, 'message' => 'Role created successfully']);
        } catch (\Exception $e) {
            $errorBag = ['message' => $e->getMessage()];
            return API_Response(500, false, ['message' => 'Error creating role', 'error' => $errorBag]);
        }
    }

    public function deleteRole($role)
    {
        try {
            Role::where('name', $role)->delete();

            return API_Response(200, true, ['message' => 'Role deleted successfully']);
        } catch (\Exception $e) {
            $errorBag = ['message' => $e->getMessage()];
            return API_Response(500, false, ['message' => 'Error deleting role', 'error' => $errorBag]);
        }
    }
}
