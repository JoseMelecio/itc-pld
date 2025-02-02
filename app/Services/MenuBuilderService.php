<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Support\Facades\Log;

class MenuBuilderService
{
    public static function AllPermissions(): \Illuminate\Database\Eloquent\Collection|array|\Illuminate\Support\Collection
    {
        $permissions = Permission::where('heading', true)->get();
        foreach ($permissions as $key => $permission) {
            $permissions[$key]['children'] = $permission->children;

            foreach ($permissions[$key]['children'] as $keyChildren => $childPermission) {
                $permissions[$key]['children'][$keyChildren]['children'] = $childPermission->children;
            }
        }

        return $permissions;
    }

    public static function allPermissionsTable(): array
    {
        $table = [];
        $permissions = self::AllPermissions();

        foreach ($permissions as $header) {
            $table[] = [
                'id' => $header->id,
                'selected' => false,
                'header' => $header->menu_label,
                'menu' => null,
                'option' => null,
            ];
            foreach($header['children'] as $menu) {
                $table[] = [
                    'id' => $menu->id,
                    'selected' => false,
                    'header' => null,
                    'menu' => $menu->menu_label,
                    'option' => null,
                ];

                foreach($menu->children as $option) {
                    $table[] = [
                        'id' => $option->id,
                        'selected' => false,
                        'header' => null,
                        'menu' => null,
                        'option' => $option->menu_label,
                    ];
                }
            }
        }

        return $table;
    }

    public static function currentPermissions(array $allPermissionsTable, array $selectedIdPermissions): array
    {
        foreach ($allPermissionsTable as $key => $permission) {
            $allPermissionsTable[$key]['selected'] = in_array($permission['id'], $selectedIdPermissions);
        }

        return $allPermissionsTable;
    }

}
