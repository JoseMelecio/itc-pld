<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Support\Facades\Log;

class MenuBuilderService
{
    /**
     * Retrieve all permissions with their children recursively.
     *
     * @return \Illuminate\Database\Eloquent\Collection|array|\Illuminate\Support\Collection
     */
    public static function AllPermissions(array $selectedPermissions = null): \Illuminate\Database\Eloquent\Collection|array|\Illuminate\Support\Collection
    {
        $permissions = Permission::where('heading', true)->get();
        foreach ($permissions as $key => $permission) {
            $permissions[$key]['selected'] = !empty($selectedPermissions) && in_array($permission->id, $selectedPermissions);
            $permissions[$key]['sub'] = $permission->children;

            foreach ($permissions[$key]['sub'] as $keyChildren => $childPermission) {
                $permissions[$key]['sub'][$keyChildren]['selected'] = !empty($selectedPermissions) && in_array($childPermission->id, $selectedPermissions);
                $permissions[$key]['sub'][$keyChildren]['sub'] = $childPermission->children;

                foreach ($permissions[$key]['sub'][$keyChildren]['sub'] as $keyChildren2 => $childPermission2) {
                    $permissions[$key]['sub'][$keyChildren]['sub'][$keyChildren2]['selected'] = !empty($selectedPermissions) && in_array($childPermission2->id, $selectedPermissions);
                }
            }
        }

        return $permissions;
    }

    /**
     * Retrieves all permissions in a structured table format based on the selected permissions.
     *
     * @param array $selectedPermission The selected permissions to be included in the table
     * @return array The structured table of permissions
     */
    public static function allPermissionsTable(array $selectedPermissions = null): array
    {
        $table = [];
        $permissions = self::AllPermissions($selectedPermissions);
        foreach ($permissions as $header) {
            $table[] = [
                'id' => $header->id,
                'selected' => $header->selected,
                'menu_label' => $header->menu_label,
                'heading' => $header->heading,
                'to' => $header->to,
                'icon' => $header->icon,
                'menu' => null,
                'option' => null,
            ];
            foreach($header['sub'] as $menu) {
                $table[] = [
                    'id' => $menu->id,
                    'selected' => $menu->selected,
                    'menu_label' => null,
                    'heading' => $menu->heading,
                    'to' => $menu->to,
                    'icon' => $menu->icon,
                    'menu' => $menu->menu_label,
                    'option' => null,
                ];

                foreach($menu->children as $option) {
                    $table[] = [
                        'id' => $option->id,
                        'selected' => $option->selected,
                        'menu_label' => null,
                        'heading' => $option->heading,
                        'to' => $option->to,
                        'icon' => $option->icon,
                        'menu' => null,
                        'option' => $option->menu_label,
                    ];
                }
            }
        }

        return $table;
    }

    public static function menuJSON(array $selectedPermissions = null): array
    {
        $menuJSON = [];
        $permissions = self::AllPermissions($selectedPermissions);

        foreach ($permissions as $keyHeader => $header) {
            $item = [];

            if ($header->name == "dashboard") {
                $item['name'] = $header->menu_label;
                $item['to'] = $header->to;
                $item['icon'] = $header->icon;

                if ($header->selected) {
                    $menuJSON[] = $item;
                }

            } else {

                $item['name'] = $header->menu_label;
                if ($header->heading) {
                    $item['heading'] = $header->heading;
                } else {
                    $item['to'] = $header->to;
                    $item['icon'] = $header->icon;
                }

                if ($header->selected) {
                    $menuJSON[] = $item;
                }


                foreach ($header->children as $menu) {
                    $subMenu = [];
                    $subMenu['name'] = $menu->menu_label;

                    $subMenu['to'] = $menu->to;
                    $subMenu['icon'] = $menu->icon;


                    foreach ($menu->children as $option) {
                        $optionSubmenu = [];
                        $optionSubmenu['name'] = $option->menu_label;

                        $optionSubmenu['to'] = $option->to;
                        $optionSubmenu['icon'] = $option->icon;

                        if($option->selected) {
                            $subMenu['sub'][] = $optionSubmenu;
                        }
                    }

                    if ($menu->selected) {
                        $menuJSON[] = $subMenu;
                    }
                }

            }

        }
        return $menuJSON;
    }


}
