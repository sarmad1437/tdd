<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __invoke(Request $request)
    {
        $path = $request->file('file')->store('uploads');

        return success($path);
    }
}
