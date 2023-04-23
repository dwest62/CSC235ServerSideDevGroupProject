<?php

class Tag
{
    private int $tag_id;
    private int $tag_type;
    private string $tag_type_name;
    private string $tag_name;

    /**
     * @param int|null $id
     * @param int|null $type
     * @param string|null $name
     */
    public function __construct(?int $id = 0, ?int $type = 0, ?string $name = "", ?string $tag_type_name = "")
    {
        $this->tag_id = $id;
        $this->tag_type = $type;
        $this->tag_name = $name;
        $this->tag_type_name = $tag_type_name;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->tag_id;
    }

    /**
     * @param int $tag_id
     */
    public function setId(int $tag_id): void
    {
        $this->tag_id = $tag_id;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->tag_type;
    }

    /**
     * @param int $tag_type
     */
    public function setType(int $tag_type): void
    {
        $this->tag_type = $tag_type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->tag_name;
    }
    /**
     * @param string $tag_name
     */
    public function setName(string $tag_name): void
    {
        $this->tag_name = $tag_name;
    }

    /**
     * @return string
     */
    public function getTagTypeName(): string
    {
        return $this->tag_type_name;
    }

    /**
     * @param string $tag_type_name
     */
    public function setTagTypeName(string $tag_type_name): void
    {
        $this->tag_type_name = $tag_type_name;
    }


}