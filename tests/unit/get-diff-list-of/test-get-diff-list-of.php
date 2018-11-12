<?php
declare (strict_types = 1);

require __DIR__ . '/../../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

final class DiffListOfTest extends TestCase
{
    private function get_diff_instance_of_specific_cases($rev_case, $prd_case)
    {
        $static_maker = $this->createMock(\Static_Maker\Deploy_Extra\Static_Maker::class);

        $path_mock = $this->getMockBuilder(Static_Maker\Deploy_Extra\Path::class)
            ->setMethods(['get_local_production_path', 'get_revision_path'])
            ->getMock();
        $path_mock->method('get_revision_path')
            ->willReturn(__DIR__ . '/diffs/case' . $rev_case);
        $path_mock->method('get_local_production_path')
            ->willReturn(__DIR__ . '/diffs/case' . $prd_case);

        return new Static_Maker\Deploy_Extra\Diff($path_mock, $static_maker);
    }

    public function testIsDiffCorrectFromCase1ToCase2(): void
    {
        $rev_case = '1';
        $prd_case = '2';

        $diff = $this->get_diff_instance_of_specific_cases($rev_case, $prd_case);

        $expected = [
            [
                'file' => '/second',
                'status' => 'deleted',
            ],
        ];

        $diff_out = $diff->get_diff_list('case' . $rev_case);
        $this->assertEquals($expected, $diff_out);
    }

    public function testIsDiffCorrectFromCase3ToCase2(): void
    {
        $rev_case = '3';
        $prd_case = '2';

        $diff = $this->get_diff_instance_of_specific_cases($rev_case, $prd_case);

        $expected = [
            [
                'file' => '/third',
                'status' => 'added',
            ],
            [
                'file' => '/second',
                'status' => 'modified',
            ],
            [
                'file' => '/first',
                'status' => 'deleted',
            ],
        ];
        $diff_out = $diff->get_diff_list('case' . $rev_case);

        $this->assertEquals($expected, $diff_out);
    }

    public function testIsDiffCorrectFromCase3ToCase4(): void
    {
        $rev_case = '3';
        $prd_case = '4';

        $diff = $this->get_diff_instance_of_specific_cases($rev_case, $prd_case);

        $expected = [
            [
                'file' => '/third',
                'status' => 'added',
            ],
            [
                'file' => '/second',
                'status' => 'added',
            ],
        ];
        $diff_out = $diff->get_diff_list('case' . $rev_case);

        $this->assertEquals($expected, $diff_out);
    }

    public function testIsDiffCorrectFromCase4ToCase3(): void
    {
        $rev_case = '4';
        $prd_case = '3';

        $diff = $this->get_diff_instance_of_specific_cases($rev_case, $prd_case);

        $expected = [
            [
                'file' => '/third',
                'status' => 'deleted',
            ],
            [
                'file' => '/second',
                'status' => 'deleted',
            ],
        ];
        $diff_out = $diff->get_diff_list('case' . $rev_case);

        $this->assertEquals($expected, $diff_out);
    }
}
