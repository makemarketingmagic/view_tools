<?php

namespace Makemarketingmagic\ViewTools\Tests\Unit;

use Makemarketingmagic\ViewTools\Services\ArrayHelper;
use Makemarketingmagic\ViewTools\Tests\TestCase;

class ArrayHelperTest extends TestCase
{

    public function testNestedKeys()
    {
        $data = [
            'nothing' => null,
            'nope' => false,
            'top' => [
                'sub' => [
                    'sub one' => 'sub one value',
                    'sub two' => 'sub two value',
                    'sub three' => 'sub three value',
                    'sub four' => 'sub four value',
                    'bottom' => [
                        'bottom one',
                        'bottom two',
                        'bottom three',
                        'bottom four'
                    ]
                ],
                'sub2' => [
                    'sub2 one' => 'sub2 one value',
                    'sub2 two' => 'sub2 two value',
                    'sub2 three' => 'sub2 three value',
                    'sub2 four' => 'sub2 four value',
                ]
            ]
        ];
        $arrayHelper = new ArrayHelper();

        // Test existence of key's
        $this->assertTrue($arrayHelper->has('nothing', $data));
        $this->assertTrue($arrayHelper->has('nope', $data));
        $this->assertTrue($arrayHelper->has('top', $data));
        $this->assertTrue($arrayHelper->has('top.sub', $data));
        $this->assertTrue($arrayHelper->has('top.sub.bottom', $data));

        $this->assertFalse($arrayHelper->has('foo', $data));
        $this->assertFalse($arrayHelper->has('top.sub.foo', $data));

        // Test existing data
        $default = 'not an existing value!';

        $result = $arrayHelper->get('nothing', $data, $default);
        $this->assertSame($data['nothing'], $result);

        $result = $arrayHelper->get('top', $data, $default);
        $this->assertSame($data['top'], $result);

        $result = $arrayHelper->get('top.sub', $data, $default);
        $this->assertSame($data['top']['sub'], $result);

        $result = $arrayHelper->get('top.sub2', $data, $default);
        $this->assertSame($data['top']['sub2'], $result);

        $result = $arrayHelper->get('top.sub.bottom', $data, $default);
        $this->assertSame($data['top']['sub']['bottom'], $result);

        // Test non-existing data
        $result = $arrayHelper->get('pot', $data, $default);
        $this->assertSame($default, $result);

        $result = $arrayHelper->get('top.bus', $data, $default);
        $this->assertSame($default, $result);

        $result = $arrayHelper->get('i do kno', $data, $default);
        $this->assertSame($default, $result);
    }
}
