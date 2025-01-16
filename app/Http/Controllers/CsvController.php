<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->isMethod('post')) {
            return view('csv');
        }

        $file = $request->file('file');

        try {
            $contents = fopen($file->getRealPath(), 'r');

            $csvData = [];
            while (($row = fgetcsv($contents)) !== false)
            {
                $csvData[] = array_filter($row);
            }
            fclose($contents);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect('/');
        }

        $people = [];
        foreach ($csvData as $row) {
            $name = reset($row);
            $name = trim($name);
            $name = explode(' ', $name);

            $hasMultiple = array_intersect(['&', 'and'], $name);
            if ($hasMultiple === []) {
                $people[] = $this->processName($name);

                continue;
            }

            $key = key($hasMultiple);
            // Everything before the 'and' or '&'
            $firstName = array_slice($name, 0, $key);
            // Everything after the 'and' or '&'
            $secondName = array_slice($name, $key);
            array_shift($secondName);
            $people[] = $this->processName($secondName);

            array_shift($secondName);
            $firstName = array_merge($firstName, $secondName);
            $people[] = $this->processName($firstName);
        }

        $people = array_filter($people);

        return view('csv')->with('people', $people);
    }

    private function processName(array $name)
    {
        // Title and surname are required, if the array has less than 2
        // values don't include it
        if (count($name) < 2) {
            return null;
        }

        return [
            'title' => $name[0],
            'first_name' => $name[1],
            // If we have at least 3 names, include an initial
            'initial' => count($name) > 3 ? $name[2] : null,
            // We may have 3 or 4 names, use the last as the surname
            'last_name' => end($name),
        ];
    }
}
