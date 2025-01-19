<?php
namespace App\Models;

class Person
{
    public function __construct(private array $pieces)
    {
        //
    }

    public function toArray(): array
    {
        // Title and surname are required, if the array has less than 2
        // values don't include it
        if (count($this->pieces) < 2) {
            return [];
        }

        $count = count($this->pieces);
        $end = end($this->pieces);

        return [
            'title' => $this->pieces[0],
            'first_name' => $this->pieces[1],
            // If we have at least 3 names, include an initial
            'initial' => $count > 3 ? $this->pieces[2] : null,
            // We may have 3 or 4 names, use the last as the surname
            'last_name' => $this->pieces[1] != $end ? $end : null,
        ];
    }
}
