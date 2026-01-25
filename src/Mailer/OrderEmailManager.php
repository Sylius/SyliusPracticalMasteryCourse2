<?php

declare(strict_types=1);

namespace App\Mailer;

use Sylius\Bundle\CoreBundle\Mailer\OrderEmailManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Webmozart\Assert\Assert;

final class OrderEmailManager implements OrderEmailManagerInterface
{
    public function __construct(
        private SenderInterface $emailSender,
        private ProductRepositoryInterface $productRepository,
    ) {
    }

    public function sendConfirmationEmail(OrderInterface $order): void
    {
        // TODO: Implement sendConfirmationEmail() method.
    }

    public function resendConfirmationEmail(OrderInterface $order): void
    {
        $email = $order->getCustomer()->getEmail();
        Assert::notNull($email);

        $channel = $order->getChannel();
        $localeCode = $order->getLocaleCode();

        $this->emailSender->send(
            Emails::ORDER_CONFIRMATION_RESENT,
            [$email],
            [
                'order' => $order,
                'channel' => $channel,
                'localeCode' => $localeCode,
                'products' => $this->productRepository->findLatestByChannel($channel, $localeCode, 4),
            ],
        );
    }
}
