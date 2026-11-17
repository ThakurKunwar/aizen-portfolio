<?php

namespace Tests\Unit;

use App\Enums\RepoProvider;
use App\Support\RepositoryUrl;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RepositoryUrlTest extends TestCase
{
    public function test_it_reads_a_plain_github_link(): void
    {
        $url = RepositoryUrl::parse('https://github.com/ThakurKunwar/shop-shop');

        $this->assertSame(RepoProvider::GitHub, $url->provider);
        $this->assertSame('ThakurKunwar/shop-shop', $url->path);
    }

    public function test_it_ignores_www_dot_git_and_extra_pages(): void
    {
        $url = RepositoryUrl::parse('https://www.github.com/ThakurKunwar/shop-shop.git');
        $this->assertSame('ThakurKunwar/shop-shop', $url->path);

        $url = RepositoryUrl::parse('https://github.com/ThakurKunwar/shop-shop/tree/main/app');
        $this->assertSame('ThakurKunwar/shop-shop', $url->path);
    }

    public function test_gitlab_keeps_nested_groups_and_drops_project_pages(): void
    {
        $url = RepositoryUrl::parse('https://gitlab.com/group/sub/project/-/tree/main');

        $this->assertSame(RepoProvider::GitLab, $url->provider);
        $this->assertSame('group/sub/project', $url->path);
    }

    public function test_a_company_gitlab_host_is_recognised(): void
    {
        $url = RepositoryUrl::parse('https://git.company.com/team/app', 'git.company.com');

        $this->assertSame(RepoProvider::GitLab, $url->provider);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function badLinks(): array
    {
        return [
            'unknown host'      => ['https://bitbucket.org/a/b'],
            'not a link'        => ['not a url'],
            'wrong scheme'      => ['ftp://github.com/a/b'],
            'owner only'        => ['https://github.com/onlyowner'],
            'host in the path'  => ['https://evil.com/github.com/a/b'],
        ];
    }

    #[DataProvider('badLinks')]
    public function test_it_rejects_links_it_does_not_understand(string $link): void
    {
        $this->expectException(InvalidArgumentException::class);

        RepositoryUrl::parse($link);
    }
}
