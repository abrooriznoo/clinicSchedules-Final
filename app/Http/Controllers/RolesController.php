<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Roles::all();

        return view('roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        // Logic to create a new role
        return response()->json(['message' => 'Role created successfully']);
    }

    public function update(Request $request, $id)
    {
        // Logic to update an existing role
        return response()->json(['message' => 'Role updated successfully']);
    }

    public function destroy($id)
    {
        // Logic to delete a role
        return response()->json(['message' => 'Role deleted successfully']);
    }
}
