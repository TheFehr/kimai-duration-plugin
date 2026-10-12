<?php

/*
 * This file is part of the GlorpenDurationBundle for Kimai 2.
 * All rights reserved by Arkadiusz Dzięgiel (https://glorpen.eu).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\TheFehrDurationBundle\EventSubscriber;

use App\Entity\ActivityMeta;
use App\Entity\CustomerMeta;
use App\Entity\MetaTableTypeInterface;
use App\Entity\ProjectMeta;
use App\Event\ActivityMetaDefinitionEvent;
use App\Event\ActivityMetaDisplayEvent;
use App\Event\CustomerMetaDefinitionEvent;
use App\Event\CustomerMetaDisplayEvent;
use App\Event\ProjectMetaDefinitionEvent;
use App\Event\ProjectMetaDisplayEvent;
use KimaiPlugin\TheFehrDurationBundle\Timesheet\Rounding\OverriddenDurationRounding;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Constraints;

class MetaFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ActivityMetaDefinitionEvent::class => ['loadActivityMeta', 200],
            ActivityMetaDisplayEvent::class => ['loadActivityFields', 200],
            ProjectMetaDefinitionEvent::class => ['loadProjectMeta', 200],
            ProjectMetaDisplayEvent::class => ['loadProjectFields', 200],
            CustomerMetaDefinitionEvent::class => ['loadCustomerMeta', 200],
            CustomerMetaDisplayEvent::class => ['loadCustomerFields', 200]
        ];
    }

    private function setUpDurationMetaField(MetaTableTypeInterface $field): MetaTableTypeInterface
    {
        return $field->setName(OverriddenDurationRounding::META_DURATION_FIELD_NAME)
            ->setLabel('Duration rounding')
            ->setOptions(['label' => 'Rounding of the duration in minutes (0 = deactivated)', 'help' => 'Leave empty to use global values'])
            ->setType(NumberType::class)
            ->addConstraint(new Constraints\PositiveOrZero())
            ->setIsVisible(false);
    }

    private function setUpBeginningMetaField(MetaTableTypeInterface $field): MetaTableTypeInterface
    {
        return $field->setName(OverriddenDurationRounding::META_BEGIN_FIELD_NAME)
            ->setLabel('Beginning rounding')
            ->setOptions(['label' => 'Rounding of the Beginning in minutes (0 = deactivated)', 'help' => 'Leave empty to use global values'])
            ->setType(NumberType::class)
            ->addConstraint(new Constraints\PositiveOrZero())
            ->setIsVisible(false);
    }

    private function setUpEndingMetaField(MetaTableTypeInterface $field): MetaTableTypeInterface
    {
        return $field->setName(OverriddenDurationRounding::META_END_FIELD_NAME)
            ->setLabel('Ending rounding')
            ->setOptions(['label' => 'Rounding of the Ending in minutes (0 = deactivated)', 'help' => 'Leave empty to use global values'])
            ->setType(NumberType::class)
            ->addConstraint(new Constraints\PositiveOrZero())
            ->setIsVisible(false);
    }

    public function loadActivityMeta(ActivityMetaDefinitionEvent $event): void
    {
        $this->loadMeta($event, ActivityMeta::class);
    }

    public function loadActivityFields(ActivityMetaDisplayEvent $event): void
    {
        $this->loadFields($event, ActivityMeta::class);
    }

    public function loadProjectMeta(ProjectMetaDefinitionEvent $event): void
    {
        $this->loadMeta($event, ProjectMeta::class);
    }

    public function loadProjectFields(ProjectMetaDisplayEvent $event): void
    {
        $this->loadFields($event, ProjectMeta::class);
    }

    public function loadCustomerMeta(CustomerMetaDefinitionEvent $event): void
    {
        $this->loadMeta($event, CustomerMeta::class);
    }

    public function loadCustomerFields(CustomerMetaDisplayEvent $event): void
    {
        $this->loadFields($event, CustomerMeta::class);
    }

    private function loadMeta($event, $metaKlass): void
    {
        $event->getEntity()
            ->setMetaField($this->setUpDurationMetaField(new $metaKlass()));
        $event->getEntity()
            ->setMetaField($this->setUpBeginningMetaField(new $metaKlass()));
        $event->getEntity()
            ->setMetaField($this->setUpEndingMetaField(new $metaKlass()));
    }

    private function loadFields($event, $metaKlass): void
    {
        $event->addField($this->setUpDurationMetaField(new $metaKlass()));
        $event->addField($this->setUpBeginningMetaField(new $metaKlass()));
        $event->addField($this->setUpEndingMetaField(new $metaKlass()));
    }
}
