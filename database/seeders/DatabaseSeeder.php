<?php

namespace Database\Seeders;


use Database\Seeders\Auth\{
    ModuleSeeder,
    PermissionSeeder,
    RoleSeeder,
    RoleUserSeeder,
};
use Database\Seeders\Examples\ExampleTypeSeeder;
use Database\Seeders\Base\{
    CategorySeeder,
    CharacteristicProductSeeder,
    CharacteristicSeeder,
    ClientCreditSeeder,
    ColorSeeder,
    DesignerProviderSeeder,
    DesignerSeeder,
    DiscountSeeder,
    ItemSeeder,
    MarketplaceSeeder,
    PaymentTypeSeeder,
    ProductImageSeeder,
    ProductProviderSeeder,
    InvoiceFileSeeder,
    InvoiceSeeder,
    ProductSeeder,
    ProductTagSeeder,
    ProductVariantSeeder,
    ProviderSeeder,
    SizeSeeder,
    StoreSeeder,
    TagSeeder,
    VariantSeeder,
    CountrySeeder,
    EventSeeder,
    EventTypeSeeder,
    MarketplaceCodeSeeder,
    NotificationSeeder,
    SupplySeeder,
    SuppliedItemSeeder,
    SupplyTransferSeeder,
    SkuSeeder,
    PricingSchemeSeeder,
    ShippingPriceSeeder,
    CouponOrderMarketplaceSeeder,
    CouponSeeder,
};
use Database\Seeders\Marketplace\{
    OrderMarketplaceSeeder,
    ItemOrderMarketplaceSeeder,
    PaymentOrderMarketplaceSeeder,
    RentDetailMarketplaceSeeder,
};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ModuleSeeder::class,
            RoleSeeder::class,
            ShippingPriceSeeder::class,
            EventTypeSeeder::class,
        ]);

        if (config('services.conspiracy.sync_enabled')) {
            // Only these seeders are needed if the database is about to be synced
            $this->call([
                MarketplaceSeeder::class,
                StoreSeeder::class,
                UserSeeder::class,
                CategorySeeder::class,
                PricingSchemeSeeder::class,
                PaymentTypeSeeder::class,
                InvoiceSeeder::class,
                CountrySeeder::class,
            ]);
        } else {
            $this->call([
                MarketplaceSeeder::class,
                StoreSeeder::class,
                UserSeeder::class,
                PermissionSeeder::class,

                RoleUserSeeder::class,

                // Base
                CountrySeeder::class,
                CategorySeeder::class,
                CharacteristicSeeder::class,
                ColorSeeder::class,
                SizeSeeder::class,
                EventSeeder::class,
                DesignerSeeder::class,
                PaymentTypeSeeder::class,
                InvoiceSeeder::class,
                InvoiceFileSeeder::class,
                ProviderSeeder::class,
                SkuSeeder::class,
                VariantSeeder::class,
                PricingSchemeSeeder::class,
                ProductSeeder::class,
                TagSeeder::class,
                ProductImageSeeder::class,
                ProductTagSeeder::class,
                ProductVariantSeeder::class,
                ItemSeeder::class,
                DesignerProviderSeeder::class,
                ProductProviderSeeder::class,
                CharacteristicProductSeeder::class,
                SupplySeeder::class,
                SupplyTransferSeeder::class,
                SuppliedItemSeeder::class,
                NotificationSeeder::class,
                MarketplaceCodeSeeder::class,
                CouponSeeder::class,
                ClientCreditSeeder::class,
                
                // Market Place
                OrderMarketplaceSeeder::class,
                PaymentOrderMarketplaceSeeder::class,
                ItemOrderMarketplaceSeeder::class,
                RentDetailMarketplaceSeeder::class,
                CouponOrderMarketplaceSeeder::class,
                DiscountSeeder::class,
                
                // Example
                ExampleTypeSeeder::class,
            ]);
        }
    }
}
