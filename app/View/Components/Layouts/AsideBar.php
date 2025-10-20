<?php

namespace App\View\Components\Layouts;

use App\Enums\Auth\{
    ModuleAliases,
    PermissionTypes,
    RoleAliases
};
use App\Traits\Base\Permission\NeedsPermissions;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use InvalidArgumentException;

class AsideBar extends Component
{
    use NeedsPermissions;

    public array $asideLinks;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $asideLinks = [
            'Productos' => $this->buildElement(
                'ki-package',
                [
                    'Inicio' => 'base.product.index',
                    'Crear' => 'base.product.full_create.view',
                    'Búsqueda' => [
                        'route' => 'base.item.filter.view',
                        'module' => ModuleAliases::ITEM,
                    ],
                ],
                module: ModuleAliases::PRODUCT,
            ),
            'Articulos' => $this->buildElement('ki-add-item', [
                'Inicio' => 'item.index',
                'Agregar Inventario' => [
                    'route' => 'stock.add.view',
                    'module' => ModuleAliases::ITEM,
                    'permissions' => [PermissionTypes::CREATE],
                ],
                'Llegada' => [
                    'route' => 'supply.show_store',
                    'module' => ModuleAliases::SUPPLY,
                    'allowed_roles' => [RoleAliases::INVENTORY],
                    'parameters' => [
                        'storeId' => ':user.employee.store_id'
                    ]
                    // 'permissions' => [PermissionTypes::CREATE],
                ],
            ], prefix: 'base', module: ModuleAliases::ITEM),
            'Ordenes' => $this->buildElement(
                'ki-handcart',
                [
                    'Inicio' => 'order_marketplace.index',
                    'Crear' => 'order_marketplace.full_create.view',
                ],
                prefix: 'marketplace',
                module: ModuleAliases::ORDER
            ),
            'Reportes' => $this->buildElement(
                'ki-note-2',
                [
                    'Inventario' => 'base.item.inventory.view',
                    'Órdenes' => 'marketplace.order.filter.view',
                    'Ingresos' => 'marketplace.order_marketplace.income.view',
                    'Distribuciones' => 'base.supply.filter.view',
                ],
                module: ModuleAliases::REPORT,
            ),
            'Categorías' => $this->buildElement('ki-abstract-26', [
                'Inicio' => 'index',
                'Crear' => 'create.view',
            ], prefix: 'base.category'),
            'Tallas' => $this->buildElement('ki-abstract-45', [
                'Inicio' => 'index',
                'Crear' => 'create.view',
            ], prefix: 'base.sizes'),
            'Colores' => $this->buildElement('ki-colors-square', [
                'Inicio' => 'index',
                'Crear' => 'create.view',
            ], prefix: 'base.color'),
            'Variantes' => $this->buildElement('ki-arrow-right-left', [
                'Inicio' => 'index',
                'Crear' => 'create.view',
            ], prefix: 'base.variant'),
            'Usuarios' => $this->buildElement(
                'ki-profile-user',
                [
                    'Empleados' => [
                        'route' => 'base.user.index',
                        'parameters' => [
                            'userType' => 'employee',
                        ]
                    ],
                    'Clientes' => [
                        'route' => 'base.user.index',
                        'parameters' => [
                            'userType' => 'client',
                        ]
                    ],
                    'Crear' => 'base.temporary.create',
                    'Roles' => [
                        'route' => 'auth.roles.index',
                        'module' => ModuleAliases::ROLE,
                    ],
                ],
                module: ModuleAliases::USER,
            ),
            'Distribuciones' => $this->buildElement('ki-parcel', [
                'Inicio' => 'index',
                'Crear' => 'create.view',
            ], prefix: 'base.supply'),
            'Diseñadores' => $this->buildElement('ki-abstract-15', [
                'Inicio' => 'index',
                'Crear' => 'create.view',
            ], prefix: 'base.designers'),
            'Características' => $this->buildElement('ki-lots-shopping', [
                'Inicio' => 'index',
                'Crear' => 'create.view',
            ], prefix: 'base.characteristics'),
            'Tiendas' => $this->buildElement('ki-shop', [
                'Inicio' => 'index',
                'Crear' => 'create.view',
            ], prefix: 'base.store'),
            'Facturas' => $this->buildElement('ki-duotone ki-file', [
                'Inicio' => 'index',
                'Crear' => 'create.view',
            ], prefix: 'base.invoice'),
            'Notificaciones' => $this->buildElement(
                'ki-notification',
                [
                    'Inicio' => 'base.notifications.index',
                    'Correos de notificación' => [
                        'route' => 'base.notifications.users.view',
                        'permissions' => [PermissionTypes::UPDATE],
                    ],
                ],
                module: ModuleAliases::NOTIFICATION,
            ),
            'Operación' => $this->buildElement(
                'ki-abstract-25',
                [
                    'Marketplaces' => [
                        'route' => 'base.marketplace.index',
                        'module' => ModuleAliases::MARKETPLACE,
                    ],
                    'Proveedores' => [
                        'route' => 'base.provider.index',
                        'module' => ModuleAliases::PROVIDER,
                    ],
                    'Esquemas de precios' => [
                        'route' => 'base.pricing_schemes.index',
                        'module' => ModuleAliases::PRICING_SCHEME,
                    ],
                    'Etiquetas' => [
                        'route' => 'base.tags.index',
                        'module' => ModuleAliases::TAG,
                    ],
                    'Tipos de pago' => [
                        'route' => 'base.payment_type.index',
                        'module' => ModuleAliases::PAYMENT_TYPE,
                    ],
                    'Skus' => [
                        'route' => 'base.sku.index',
                        'module' => ModuleAliases::SKU,
                    ],
                    'Eventos' => [
                        'route' => 'base.events.index',
                        'module' => ModuleAliases::EVENT,
                    ],
                    'Tipos de Evento' => [
                        'route' => 'base.event_types.index',
                        'module' => ModuleAliases::EVENT_TYPE,
                    ],
                ],
            ),
        ];

        $storeName = Auth::user()?->employeeDetail?->store?->name;

        if ($storeName === 'Almacén' && isset($asideLinks['Ordenes']['Crear'])) {
            unset($asideLinks['Ordenes']['Crear']);
        }

        $this->asideLinks = collect($asideLinks)
            ->map(fn(array $d) => collect($d)
                ->filter()
                ->toArray())
            ->filter(fn(array $d) => collect($d)
                ->reject(fn($val, $key) => $key == '_icon')
                ->isNotEmpty())->toArray();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.aside-bar');
    }

    private function buildElement(string $icon, array $routes, ModuleAliases $module = null, string $prefix = null)
    {
        $moduleKey = $module ?? Str::of($prefix)->afterLast('.')->plural()->slug();
        $filtered = [];

        foreach ($routes as $k => $data) {
            if (is_string($data)) {
                $route = $data;
                $module = $moduleKey;
                $parameters = [];
                $permissions = $this->getDefaultPermissions($data);
                $allowedRoles = null;
            } else if (is_array($data)) {
                $route = $data['route'];
                $parameters = $data['parameters'] ?? [];
                $module = $data['module'] ?? $moduleKey;
                $permissions = $data['permissions'] ?? $this->getDefaultPermissions($route);
                $allowedRoles = $data['allowed_roles'] ?? null;
            } else {
                throw new InvalidArgumentException('Data of aside element must be either a string or an array');
            }

            if (!empty($permissions) && !Auth::user()->hasAnyPermission($module, ...($permissions))) { // If the user has no permission
                continue; // Ignore this route
            }

            if ($allowedRoles !== null && !Auth::user()->hasAnyRole($allowedRoles)) {
                continue;
            }

            if ($route === 'supply.show_store') {
                $parameters['storeId'] = Auth::user()?->employeeDetail?->store_id ?? null;
            }

            $routeName = collect([$prefix, $route])
                ->filter()
                ->join('.');

            $filtered[$k] = route($routeName, $parameters);
        }

        return [
            '_icon' => $icon,
            ...$filtered,
        ];
    }
}
