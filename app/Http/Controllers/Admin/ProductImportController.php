<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductImportController extends Controller
{
    public function importForm()
    {
        return view('admin.products.import');
    }
}
