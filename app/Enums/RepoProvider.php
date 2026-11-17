<?php

namespace App\Enums;

enum RepoProvider: string
{
    case GitHub = 'github';
    case GitLab = 'gitlab';

    public function label(): string
    {
        return match ($this) {
            self::GitHub => 'GitHub',
            self::GitLab => 'GitLab',
        };
    }
}
