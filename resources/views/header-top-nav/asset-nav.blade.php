@php
// Fetch all menus where parent_id = 39
$menus = DB::table('nav_menus_right_top')->where('parent_id', 39)->orderBy('order_by')->get();

// Fetch all possible submenus where parent_id exists in the menu IDs
$subMenus = DB::table('nav_menus_right_top')->whereIn('parent_id',
$menus->pluck('menu_id'))->orderBy('order_by')->get();

$menuIds = $menus->pluck('menu_id')->toArray(); // Get all menu IDs
$moreMenus = $menus->where('more_link', 'yes'); // Get "More" menu items
@endphp

<div class="col-7 text-end px-4">
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        @foreach ($menus as $menu)
        @php
        // Get submenus for this menu item
        $childMenus = $subMenus->where('parent_id', $menu->menu_id);
        $hasSubMenu = $childMenus->isNotEmpty();
        @endphp

        @if ($menu->more_link === 'no')
        <div class="btn-group">
            <a href="{{ route($menu->menu_link) }}"
                class="btn {{ Route::currentRouteName() === $menu->menu_link ? 'btn-info text-white' : 'btn-secondary text-white' }}  @if ($hasSubMenu) pe-0 @endif">
                {{ $menu->menu_name }}


            </a>
            @if ($hasSubMenu)
            <button
                class="btn {{ Route::currentRouteName() === $menu->menu_link ? 'btn-info' : 'btn-secondary text-white' }} dropdown-toggle p-0 px-1"
                data-bs-toggle="dropdown" aria-expanded="false">
            </button>

            <ul class="dropdown-menu">
                @foreach ($childMenus as $subMenu)
                <li>
                    <a class="dropdown-item {{ Route::currentRouteName() === $subMenu->menu_link ? 'bg-info text-white' : 'text-info' }}"
                        href="{{ route($subMenu->menu_link) }}">
                        {{ $subMenu->menu_name }}
                    </a>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
        @endif
        @endforeach

        @if ($moreMenus->isNotEmpty())
        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button"
                class="btn dropdown-toggle {{ in_array(Route::currentRouteName(), $moreMenus->pluck('menu_link')->toArray()) ? 'btn-info text-white' : 'btn-secondary text-white' }}"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                More
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                @foreach ($moreMenus as $menu)
                <a class="dropdown-item {{ Route::currentRouteName() === $menu->menu_link ? 'bg-info text-white' : 'text-info' }}"
                    href="{{ route($menu->menu_link) }}">
                    {{ $menu->menu_name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>