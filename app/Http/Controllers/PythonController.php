<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PythonController extends Controller
{
    public function runScript(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = $request->input('query');

        // Define paths
        $venvPythonPath = base_path('venv/bin/python'); // Path to the Python interpreter in your virtual environment
        $scriptPath = base_path('python-scripts/chatgpt-01.py');
        $embeddingsPath = base_path('python-scripts/CW360_Dataset.csv');

        // Command to execute
        $process = new Process([$venvPythonPath, $scriptPath, $embeddingsPath, $query]);

        try {
            $process->mustRun();
            $output = $process->getOutput();
            return response()->json(['output' => $output]);
        } catch (ProcessFailedException $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
