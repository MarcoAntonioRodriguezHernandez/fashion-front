import { usePage } from "@inertiajs/vue3";

const page = usePage();

/**
 * If any alias es added, removed or modified, please update the enum as well (app/Enums/Auth/ModuleAliases.php)
 */
export const ModuleAliases = Object.freeze({
    USER: 'users',
    PRODUCT: 'products',
    ITEM: 'items',
    VARIANT: 'variants',
    CATEGORY: 'categories',
    TAG: 'tags',
    SIZE: 'sizes',
    COLOR: 'colors',
    STORE: 'stores',
    DESIGNER: 'designers',
    CHARACTERISTIC: 'characteristics',
    PRICING_SCHEME: 'pricing-schemes',
    SKU: 'skus',
    MARKETPLACE: 'marketplaces',
    PROVIDER: 'providers',
    SUPPLY: 'supplies',
    PAYMENT_TYPE: 'payment-types',
    COUNTRY: 'countries',
    INVOICE: 'invoices',
    NOTIFICATION: 'notifications',
    SHIPPING_PRICE: 'shipping-prices',
    EVENT: 'events',
    EVENT_TYPE: 'event_types',
    ROLE: 'roles',
    REPORT: 'reports',
    ORDER: 'orders',
    SUPER_ORDER: 'super-orders',
});

export const PermissionTypes = Object.freeze({
    READ: 1,
    UPDATE: 2,
    CREATE: 3,
});

export default {
    user: () => page.props.authUser,
    check: () => page.props.authUser !== null,
    hasAnyPermission: (moduleKey, ...permissions) => {
        if (!page.props.authUser)
            return false;

        const userPermissions = page.props.authUser.permissions[moduleKey] || [];

        return permissions.some((p) => userPermissions.includes(p));
    }
}
