<?php

namespace App\Http\Controllers;

use App\Models\Results;
use Illuminate\Http\Request;

class ResultsHistory extends Controller
{
    public function show()
    {
        $results = Results::all();

        return view('welcome', ['results' => $results ,'test' => 'test']);
    }

    public function delete()
    {
        Results::truncate();
        return response()->json(['message' => 'All records deleted successfully']);
    }
}
