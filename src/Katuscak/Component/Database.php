<?php

namespace Katuscak\Component;

use Katuscak\Bridge\DatabaseConfiguration;

final class Database
{
    /** @var \mysqli */
    private $mysqli;

    public function __construct(DatabaseConfiguration $configuration)
    {
        $dns = $configuration->get("server");
        $user = $configuration->get("user");
        $password = $configuration->get("password");
        $database = $configuration->get("database");

        $mysqli = new \mysqli($dns, $user, $password, $database);

        if ($mysqli->connect_errno) {
            throw new \Exception("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }

        $this->mysqli = $mysqli;
    }

    public function query(string $sql, ...$arg)
    {
        foreach ($arg as $key => $value) {
            $sql = str_replace("%".$key, "'".$this->mysqli->real_escape_string($value)."'", $sql);
        }
        return $this->mysqli->query($sql);
    }
}