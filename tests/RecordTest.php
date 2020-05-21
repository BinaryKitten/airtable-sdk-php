<?php

declare(strict_types=1);

namespace Beachcasts\AirtableTests;

use Beachcasts\Airtable\Field;
use Beachcasts\Airtable\Record;
use PHPUnit\Framework\TestCase;

class RecordTest extends TestCase
{
    public function testIdPropertySetterAndGetter(): void
    {
        $idProperty = new \ReflectionProperty(Record::class, 'id');
        $idProperty->setAccessible(true);
        $randomId = sha1(random_bytes(10));

        $record = new Record();
        $this->assertSame('', $idProperty->getValue($record));

        $record->setId($randomId);
        $this->assertSame($randomId, $idProperty->getValue($record));

        $secondRandomId = sha1(random_bytes(10));
        $idProperty->setValue($record, $secondRandomId);
        $this->assertSame($secondRandomId, $record->getId());
    }

    public function testFieldsRelatedMethods(): void
    {
        $testField = Field::createWith(sha1(random_bytes(10)), random_int(11, 99));
        $testField2 = Field::createWith(sha1(random_bytes(10)), random_int(11, 99));
        $fieldsProperty = new \ReflectionProperty(Record::class, 'fields');
        $fieldsProperty->setAccessible(true);

        $record = new Record();
        //empty array at start
        $this->assertIsArray($fieldsProperty->getValue($record));
        $this->assertEmpty($fieldsProperty->getValue($record));

        $record->addField($testField);
        // our field should be in the fieldsProperty
        $this->assertContains($testField, $fieldsProperty->getValue($record));

        $record->emptyFields();
        // our fields should be empty array
        $this->assertIsArray($fieldsProperty->getValue($record));
        $this->assertEmpty($fieldsProperty->getValue($record));

        $fieldsProperty->setValue($record, [$testField, $testField2]);
        $record->removeField($testField);
        // our fields should be empty array
        $this->assertIsArray($fieldsProperty->getValue($record));
        $this->assertSame([$testField2], $fieldsProperty->getValue($record));
    }
}
