<?php
namespace GrShareCode\Tests\Unit\Domain\CustomField\CustomFieldFilter;

use GrShareCode\CustomField\CustomField;
use GrShareCode\CustomField\CustomFieldFilter\CustomFieldForMappingFilter;
use GrShareCode\Tests\Unit\BaseTestCase;

class CustomFieldForMappingFilterTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldReturnFalseIfCustomIsOrigin()
    {
        $customField = new CustomField(
            'customFieldId',
            'origin',
            'text',
            'string'
        );

        $filter = new CustomFieldForMappingFilter();
        $this->assertFalse($filter->matches($customField));
    }

    /**
     * @test
     */
    public function shouldReturnTrueIfCustomIsTextString()
    {
        $customField = new CustomField(
            'customFieldId',
            'customFieldName',
            'text',
            'string'
        );

        $filter = new CustomFieldForMappingFilter();
        $this->assertTrue($filter->matches($customField));
    }

}
