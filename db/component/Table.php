<?php

/**
 * Table.php - abstract table class assists in DB operations
 * Written by: James West - westj4@csp.edu - April, 2023
 */
abstract class Table
{
    /**
     * @param mysqli $conn
     * @return bool
     */
    abstract public static function addTable(mysqli $conn): bool;

    /**
     * @return string
     */
    abstract public function getName(): string;

    /**
     * @param mysqli $conn
     * @param array $entry
     * @param bool $addQuotesToStringEntries
     * @return bool
     */
    public function addJSONEntry(mysqli $conn, array $entry, bool $addQuotesToStringEntries = false): bool
    {
        $cols = join(", ", array_keys($entry));

        if ($addQuotesToStringEntries) {
            foreach ($entry as $key => $value) {
                if (gettype($value) == "string") {
                    $entry[$key] = "'$value'";
                }
            }
        }

        $values = join(",", array_values($entry));
        $sql = <<<SQL
            INSERT INTO {$this->getName()}($cols) VALUES ($values)
        SQL;
        return $conn->query($sql);
    }

    /**
     * @param mysqli $conn
     * @param array $entries
     * @param bool $addQuotes
     * @return bool
     */
    public function addJSONEntries(mysqli $conn, array $entries, bool $addQuotes = false): bool
    {
        $tableEntries = $entries[$this->getName()];
        foreach ($tableEntries as $entry) {
            $result = $this->addJSONEntry($conn, $entry, $addQuotes);
            if (!$result) {
                return false;
            }
        }
        return true;
    }


    /**
     * @param mysqli $conn
     * @return string
     */
    public function getDisplay(mysqli $conn): string
    {
        $sql = <<<SQL
            SELECT * FROM {$this->getName()}
        SQL;

        $result = $conn->query($sql);

        if (!$result->num_rows > 0) {
            return "No rows to user";
        } else {
            $rows = $result->fetch_all(1);
            $fields = array_keys($rows[0]);

            $result->free();
            $html = "<table><caption>{$this->getName()}</caption>";

            $html .= Table::getRow($fields, true);
            foreach ($rows as $entry) {
                $html .= Table::getRow($entry);
            }
            $html .= "</table>";
            return $html;
        }
    }

    /**
     * @param array $values
     * @param bool $isHeading
     * @return string
     */
    private static function getRow(array $values, bool $isHeading = false): string
    {
        $openTag = $isHeading ? "<th>" : "<td>";
        $closeTag = substr($openTag, 0, 1) . "/" . substr($openTag, 1);
        $html = "<tr>";
        foreach ($values as $value) {
            $html .= $openTag . $value . $closeTag;
        }
        $html .= "</tr>";
        return $html;
    }
}
