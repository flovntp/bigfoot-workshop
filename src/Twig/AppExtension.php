<?php

namespace App\Twig;

use App\Entity\User;
use App\Service\CommentHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function __construct(private CommentHelper $commentHelper, private $userStatuses = [])
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('user_activity_text', [$this, 'getUserActivityText']),
        ];
    }

    public function getUserActivityText(User $user): string
    {
        if (!isset($this->userStatuses[$user->getId()])) {
            $this->userStatuses[$user->getId()] = $this->calculateUserActivityText($user);
        }

        return $this->userStatuses[$user->getId()];
    }

    private function calculateUserActivityText(User $user): string
    {
        $commentCount = $this->commentHelper->countRecentCommentsForUser($user);

        if ($commentCount > 50) {
            return 'bigfoot fanatic';
        }

        if ($commentCount > 30) {
            return 'believer';
        }

        if ($commentCount > 20) {
            return 'hobbyist';
        }

        return 'skeptic';
    }
}
