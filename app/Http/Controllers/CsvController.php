<?php
namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->isMethod('post')) {
            return view('csv');
        }

        $file = $request->file('file');
        if (! $file) {
            session()->flash('error', 'Please select a file to upload');

            return redirect('/');
        }

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
            // DEVTODO Put this logic into the Person model
            $row = reset($row);
            $row = trim($row);
            $row = explode(' ', $row);

            // Find where 'and' or '&' are
            $hasMultiple = array_intersect(['&', 'and'], $row);
            // Does not have multiple people - no matches of 'and' or '&'
            if ($hasMultiple === []) {
                $person = new Person($row);
                $people[] = $person->toArray();

                continue;
            }

            $key = key($hasMultiple);
            // Everything before the 'and' or '&'
            $firstName = array_slice($row, 0, $key);
            // Everything after the 'and' or '&'
            $secondName = array_slice($row, $key);
            array_shift($secondName);
            $person1 = new Person($secondName);
            $people[] = $person1->toArray();

            array_shift($secondName);
            $firstName = array_merge($firstName, $secondName);
            $person2 = new Person($firstName);
            $people[] = $person2->toArray();
        }

        $people = array_filter($people);

        return view('csv')->with('people', $people);
    }
}
