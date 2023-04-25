<?php

/**
 * Destination.php - Class represents a destination.
 * Written by: James West - westj4@csp.edu - April, 2023
 */
class Destination
{

    private int $id;

    private string $name;

    private string $description;

    private string $zip;

    private string $line_1;

    private string $line_2;

    private string $city;

    private string $image_url;

    private string $website;

    private int $len;

    /**
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string $zip
     * @param string $line_1
     * @param string $line_2
     * @param string $city
     * @param string $image_url
     * @param string $website
     * @param int $len
     */
    public function __construct(
        int    $id = 0,
        string $name = "",
        string $description = "",
        string $zip = "",
        string $line_1 = "",
        string $line_2 = "",
        string $city = "",
        string $image_url = "",
        string $website = "",
        int    $len = 0
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->zip = $zip;
        $this->line_1 = $line_1;
        $this->line_2 = $line_2;
        $this->city = $city;
        $this->image_url = $image_url;
        $this->website = $website;
        $this->len = $len;
    }

    /**
     * Set all destination values to default values
     * @return void
     */
    public function clear(): void
    {
        $this->id = 0;
        $this->name = "";
        $this->description = "";
        $this->city = "";
        $this->zip = "";
        $this->line_1 = "";
        $this->line_2 = "";
        $this->len = 0;
        $this->website = "";
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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     */
    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getLine1(): string
    {
        return $this->line_1;
    }

    /**
     * @param string $line_1
     */
    public function setLine1(string $line_1): void
    {
        $this->line_1 = $line_1;
    }

    /**
     * @return string
     */
    public function getLine2(): string
    {
        return $this->line_2;
    }

    /**
     * @param string $line_2
     */
    public function setLine2(string $line_2): void
    {
        $this->line_2 = $line_2;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->image_url;
    }

    /**
     * @param string $image_url
     */
    public function setImageUrl(string $image_url): void
    {
        $this->image_url = $image_url;
    }

    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    /**
     * @return int|null
     */
    public function getLen(): ?int
    {
        return $this->len;
    }

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
}