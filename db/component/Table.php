<?php

abstract class Table
{
    abstract public static function addTable(mysqli $conn): bool;

    abstract public function getName(): string;

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


    public function getDisplay(mysqli $conn): string
    {
        $sql = <<<SQL
            SELECT * FROM {$this->getName()}
        SQL;

        $result = $conn->query($sql);

        if (!$result->num_rows > 0) {
            return "No rows to display";
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
