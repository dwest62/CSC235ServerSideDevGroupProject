<?php

/**
 * TagType.php - Class represents a Tag Type
 * Written by: James West - westj4@csp.edu - April, 2023
 */
class TagType
{
    private int $id;
    private string $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int|null $id
     * @param string|null $name
     */
    public function __construct(?int $id = 0, ?string $name = "")
    {
        $this->id = $id;
        $this->name = $name;
    }

}