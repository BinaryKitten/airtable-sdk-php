<?php

declare(strict_types=1);

namespace Beachcasts\Airtable;

use Assert\Assert;

class Record implements \JsonSerializable
{
    private $id = '';
    private $fields = [];
    private $createTime;

    public function setId(string $id): void
    {
        Assert::that($id)
            ->notBlank('Record id must not be empty');

        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setCreatedTimeFromString(string $createdTime): void
    {
        $this->createTime = new \DateTimeImmutable($createdTime);
    }


    public function addField(Field $field): void
    {
        $this->fields[] = $field;
    }

    public function emptyFields(): void
    {
        $this->fields = [];
    }

    public function removeField(Field $fieldToDelete): void
    {
        foreach ($this->fields as $idx => $field) {
            if ($fieldToDelete->equals($field)) {
                unset($this->fields[$idx]);
                break;
            }
        }

        $this->fields = array_values($this->fields);
    }

    public function jsonSerialize()
    {
        $jsonRecord = [
            'fields' => []
        ];
        if (!empty($this->id)) {
            $jsonRecord['id'] = $this->id;
        }

        if (empty($this->fields)) {
            $jsonRecord['fields'] = new \stdClass();
            return $jsonRecord;
        }

        /** @var Field $field */
        foreach ($this->fields as $field) {
            $jsonRecord['fields'][$field->getName()] = $field->getValue();
        }

        return $jsonRecord;
    }
}
