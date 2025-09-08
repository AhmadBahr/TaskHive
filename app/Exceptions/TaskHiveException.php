<?php

namespace App\Exceptions;

use Exception;

class TaskHiveException extends Exception
{
    public static function wipLimitExceeded(string $columnName, int $limit): self
    {
        return new self("Cannot add task to '{$columnName}' - WIP limit of {$limit} reached.");
    }

    public static function unauthorizedAccess(string $resource): self
    {
        return new self("You do not have permission to access this {$resource}.");
    }

    public static function invalidColumnMove(string $columnName): self
    {
        return new self("Cannot move task to '{$columnName}' - column does not belong to this board.");
    }

    public static function taskNotFound(string $taskId): self
    {
        return new self("Task with ID '{$taskId}' not found.");
    }

    public static function boardNotFound(string $boardSlug): self
    {
        return new self("Board with slug '{$boardSlug}' not found.");
    }
}
