<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PythonController extends Controller
{
    public function runScript(Request $request,$input)
    {
        $scriptPath = base_path('python-scripts/chatgpt-01.py');
        $embeddingsPath = base_path('python-scripts/CW360_Dataset.csv');
        $query = $input;

        // Command to execute
        $process = new Process(['python', $scriptPath, $embeddingsPath, $query]);

//        try {
            $process->mustRun();
            $output = $process->getOutput();
            dd($output);
            return response()->json(['output' => $output]);
//        } catch (ProcessFailedException $exception) {
//            return response()->json(['error' => $exception->getMessage()], 500);
//        }
    }
}
