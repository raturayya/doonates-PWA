<?php

namespace App\Http\Controllers;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = [];

        return view('organizations.index', compact('organizations'));
    }
}
