<?php

namespace Models;

use App\Models\Person;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    /** @dataProvider nameProvider */
    public function testCreatePersonFromCsv(array $csvData, array $expectedResult): void
    {
        $person = new Person($csvData);

        $this->assertequals($expectedResult, $person->toArray());
    }

    static function nameProvider(): array
    {
        // Data name => passed in data, expected result
        return [
            'all fields' => [
                [
                    'Mr',
                    'Joe',
                    'A',
                    'Bloggs'
                ],
                [
                    'title' => 'Mr',
                    'first_name' => 'Joe',
                    'initial' => 'A',
                    'last_name' => 'Bloggs',
                ]
            ],
            'no initial' => [
                [
                    'Mr',
                    'Joe',
                    'Bloggs'
                ],
                [
                    'title' => 'Mr',
                    'first_name' => 'Joe',
                    'initial' => null,
                    'last_name' => 'Bloggs',
                ]
            ],
            'only firstname' => [
                [
                    'Joe',
                ],
                [
                    //
                ]
            ],
        ];
    }
}
