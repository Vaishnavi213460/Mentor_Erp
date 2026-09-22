<?php

namespace App\Enums;

enum PurchaseOrderStatus: string
{
    case DRAFT = 'DRAFT';
    case APPROVED = 'APPROVED';
    case RECEIVED = 'RECEIVED';
    case CANCELLED = 'CANCELLED';

    public function isImmutable(): bool
    {
        return in_array($this, [self::RECEIVED, self::CANCELLED]);
    }

    public function canTransitionTo(self $target): bool
    {
        if ($this->isImmutable()) {
            return false;
        }

        return match ($this) {
            self::DRAFT => in_array($target, [self::APPROVED, self::CANCELLED]),
            self::APPROVED => in_array($target, [self::RECEIVED, self::CANCELLED]),
            default => false,
        };
    }
}