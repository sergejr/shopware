<?php declare(strict_types=1);

namespace Shopware\Api\Shop\Event\ShopTemplate;

use Shopware\Api\Plugin\Event\Plugin\PluginBasicLoadedEvent;
use Shopware\Api\Shop\Collection\ShopTemplateDetailCollection;
use Shopware\Api\Shop\Event\Shop\ShopBasicLoadedEvent;
use Shopware\Api\Shop\Event\ShopTemplateConfigForm\ShopTemplateConfigFormBasicLoadedEvent;
use Shopware\Api\Shop\Event\ShopTemplateConfigFormField\ShopTemplateConfigFormFieldBasicLoadedEvent;
use Shopware\Api\Shop\Event\ShopTemplateConfigPreset\ShopTemplateConfigPresetBasicLoadedEvent;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;

class ShopTemplateDetailLoadedEvent extends NestedEvent
{
    public const NAME = 'shop_template.detail.loaded';

    /**
     * @var TranslationContext
     */
    protected $context;

    /**
     * @var ShopTemplateDetailCollection
     */
    protected $shopTemplates;

    public function __construct(ShopTemplateDetailCollection $shopTemplates, TranslationContext $context)
    {
        $this->context = $context;
        $this->shopTemplates = $shopTemplates;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): TranslationContext
    {
        return $this->context;
    }

    public function getShopTemplates(): ShopTemplateDetailCollection
    {
        return $this->shopTemplates;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];
        if ($this->shopTemplates->getPlugins()->count() > 0) {
            $events[] = new PluginBasicLoadedEvent($this->shopTemplates->getPlugins(), $this->context);
        }
        if ($this->shopTemplates->getParents()->count() > 0) {
            $events[] = new ShopTemplateBasicLoadedEvent($this->shopTemplates->getParents(), $this->context);
        }
        if ($this->shopTemplates->getShops()->count() > 0) {
            $events[] = new ShopBasicLoadedEvent($this->shopTemplates->getShops(), $this->context);
        }
        if ($this->shopTemplates->getShops()->count() > 0) {
            $events[] = new ShopBasicLoadedEvent($this->shopTemplates->getShops(), $this->context);
        }
        if ($this->shopTemplates->getChildren()->count() > 0) {
            $events[] = new ShopTemplateBasicLoadedEvent($this->shopTemplates->getChildren(), $this->context);
        }
        if ($this->shopTemplates->getConfigForms()->count() > 0) {
            $events[] = new ShopTemplateConfigFormBasicLoadedEvent($this->shopTemplates->getConfigForms(), $this->context);
        }
        if ($this->shopTemplates->getConfigFormFields()->count() > 0) {
            $events[] = new ShopTemplateConfigFormFieldBasicLoadedEvent($this->shopTemplates->getConfigFormFields(), $this->context);
        }
        if ($this->shopTemplates->getConfigPresets()->count() > 0) {
            $events[] = new ShopTemplateConfigPresetBasicLoadedEvent($this->shopTemplates->getConfigPresets(), $this->context);
        }

        return new NestedEventCollection($events);
    }
}
