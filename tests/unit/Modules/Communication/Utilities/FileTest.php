<?php
namespace Modules\Communication\Utilities;

use App\Modules\Communication\Utilities\File;

class FileTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testNameMakeSafe_WithDirtyString_ShouldSanitize()
    {
        $file = new File();

        $string = $file->nameMakeSafe('test`` string=+');

        $this->assertEquals('teststring', $string);
    }
}
