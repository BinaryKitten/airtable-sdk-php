<?php

declare(strict_types=1);

namespace Beachcasts\Airtable;

use Assert\Assert;

class Field
{
    private $name = '';
    private $value = '';

    protected function __construct()
    {
    }

    public static function createWith(string $name, $value): Field
    {
        Assert::that($name)
            ->notBlank('Field Name must not be empty');

        $field = new self();
        $field->name = $name;
        $field->value = $value;

        return $field;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(Field $field): bool
    {
        return $this->name === $field->name
            && $this->value === $field->value;
    }
}
