<?php

namespace dbfTable;

use mysqli;

interface Table
{
    public function makeTable(mysqli $conn);
}