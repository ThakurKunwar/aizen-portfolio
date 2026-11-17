<?php

namespace App\Support;

use App\Enums\RepoProvider;
use InvalidArgumentException;

/**
 * Turns a link someone pasted into "which service, which repository".
 * Pure PHP on purpose: it never touches the network, so it is easy to test,
 * and it only accepts hosts we know (no arbitrary URL is ever fetched).
 */
final readonly class RepositoryUrl
{
    private function __construct(
        public RepoProvider $provider,
        public string $host,
        public string $path,
    ) {}

    public static function parse(string $url, string $gitlabHost = 'gitlab.com'): self
    {
        $parts = parse_url(trim($url));

        if (! is_array($parts)
            || ! isset($parts['host'], $parts['scheme'])
            || ! in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            throw new InvalidArgumentException('Enter a full link, like https://github.com/user/repo.');
        }

        $host = strtolower((string) preg_replace('/^www\./i', '', $parts['host']));
        $gitlabHost = strtolower($gitlabHost);

        $provider = match (true) {
            $host === 'github.com' => RepoProvider::GitHub,
            $host === 'gitlab.com', $host === $gitlabHost => RepoProvider::GitLab,
            default => throw new InvalidArgumentException("Only github.com and {$gitlabHost} links are supported."),
        };

        $path = trim($parts['path'] ?? '', '/');

        // GitLab puts pages inside a project after "/-/" (tree, issues, ...).
        $path = explode('/-/', $path, 2)[0];
        $path = (string) preg_replace('/\.git$/', '', $path);

        $segments = array_values(array_filter(explode('/', $path), fn (string $s) => $s !== ''));

        if (count($segments) < 2) {
            throw new InvalidArgumentException('That link does not point to a repository (expected owner/name).');
        }

        // GitHub is always owner/name. GitLab allows nested groups: group/sub/name.
        if ($provider === RepoProvider::GitHub) {
            $segments = array_slice($segments, 0, 2);
        }

        return new self($provider, $host, implode('/', $segments));
    }
}
