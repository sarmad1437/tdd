<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __invoke(Request $request)
    {
        $path = $request->all();

        dd($path);

        return success('s');

    }
}
