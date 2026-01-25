<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Entity\Brand\Brand;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

final class BrandEmailManager
{
    public function __construct(
        private ChannelContextInterface $channelContext,
        private ChannelRepositoryInterface $channelRepository,
        private SenderInterface $sender,
        private string $defaultLocaleCode,
    ) {
    }

    public function sendApprovalEmail(Brand $brand): void
    {
        $email = $brand->getContactEmail();
        if ($email === null) {
            return;
        }

        $channel = $this->resolveChannel($brand);
        if ($channel === null) {
            return;
        }

        $this->sender->send(
            Emails::BRAND_APPROVAL,
            [$email],
            [
                'brand' => $brand,
                'channel' => $channel,
                'localeCode' => $this->resolveLocaleCode($brand, $channel),
            ]
        );
    }

    private function resolveChannel(Brand $brand): ?ChannelInterface
    {
        $channel = $brand->getChannels()->first();
        if ($channel instanceof ChannelInterface) {
            return $channel;
        }

        try {
            /** @var ChannelInterface $channel */
            $channel = $this->channelContext->getChannel();

            return $channel;
        } catch (ChannelNotFoundException) {
            foreach ($this->channelRepository->findEnabled() as $channel) {
                return $channel;
            }

            return null;
        }
    }

    private function resolveLocaleCode(Brand $brand, ChannelInterface $channel): string
    {
        $localeCode = $brand->getDefaultLocale()?->getCode();
        if ($localeCode !== null) {
            return $localeCode;
        }

        return $channel->getDefaultLocale()?->getCode() ?? $this->defaultLocaleCode;
    }
}
