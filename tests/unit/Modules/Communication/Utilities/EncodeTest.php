<?php
namespace Modules\Communication\Utilities;

use App\Modules\Communication\Utilities\Encode;

class EncodeTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    // tests
    public function testMd5_WithText_ShouldReturnMd5Result()
    {
        $util = new Encode;

        $this->assertEquals(md5('antony'), $util->md5('antony'));
    }
}
