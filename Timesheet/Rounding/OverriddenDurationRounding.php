<?php

/*
 * This file is part of the GlorpenDurationBundle for Kimai 2.
 * All rights reserved by Arkadiusz Dzięgiel (https://glorpen.eu).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\TheFehrDurationBundle\Timesheet\Rounding;

use App\Entity\EntityWithMetaFields;
use App\Entity\Timesheet;
use App\Timesheet\Rounding\RoundingInterface;

class OverriddenDurationRounding implements RoundingInterface
{
    public const META_DURATION_FIELD_NAME = 'thefehr_duration';
    public const META_BEGIN_FIELD_NAME = 'thefehr_begin';
    public const META_END_FIELD_NAME = 'thefehr_end';

    /**
     * @var RoundingInterface
     */
    private $vendorRounding;

    public function __construct(RoundingInterface $vendorRounding)
    {
        $this->vendorRounding = $vendorRounding;
    }

    public function getId(): string
    {
        return $this->vendorRounding->getId();
    }

    /**
     * @param EntityWithMetaFields|null $entity
     * @return int|null
     */
    private function getValue(?EntityWithMetaFields $entity, string $metaField): ?int
    {
        if ($entity === null) {
            return null;
        }

        $metaField = $entity->getMetaField($metaField);

        if ($metaField !== null) {
            return $metaField->getValue();
        }

        return null;
    }

    public function roundDuration(Timesheet $record, int $minutes): void
    {
        $duration = $this->getValue($record->getActivity(), self::META_DURATION_FIELD_NAME);
        if ($duration === null) {
            $duration = $this->getValue($record->getProject(), self::META_DURATION_FIELD_NAME);
        }
        if ($duration === null && $record->getProject() !== null) {
            $duration = $this->getValue($record->getProject()->getCustomer(), self::META_DURATION_FIELD_NAME);
        }

        if ($duration !== null) {
            $minutes = $duration;
        }

        $this->vendorRounding->roundDuration($record, $minutes);
    }

    public function roundBegin(Timesheet $record, int $minutes): void
    {
        $duration = $this->getValue($record->getActivity(), self::META_BEGIN_FIELD_NAME);
        if ($duration === null) {
            $duration = $this->getValue($record->getProject(), self::META_BEGIN_FIELD_NAME);
        }
        if ($duration === null && $record->getProject() !== null) {
            $duration = $this->getValue($record->getProject()->getCustomer(), self::META_BEGIN_FIELD_NAME);
        }

        if ($duration !== null) {
            $minutes = $duration;
        }

        $this->vendorRounding->roundBegin($record, $minutes);
    }

    public function roundEnd(Timesheet $record, int $minutes): void
    {
        $duration = $this->getValue($record->getActivity(), self::META_END_FIELD_NAME);
        if ($duration === null) {
            $duration = $this->getValue($record->getProject(), self::META_END_FIELD_NAME);
        }
        if ($duration === null && $record->getProject() !== null) {
            $duration = $this->getValue($record->getProject()->getCustomer(), self::META_END_FIELD_NAME);
        }

        if ($duration !== null) {
            $minutes = $duration;
        }

        $this->vendorRounding->roundEnd($record, $minutes);
    }
}
