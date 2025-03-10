@php
    $menus = DB::table('nav_menus_left')->where('parent_id', 30)->orderBy('order_by')->get();
@endphp
<div class="col-7 text-end px-4">
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        @foreach ($menus as $menu)
            @if ($menu->more_link === 'no')
                <a href="{{ route($menu->menu_link) }}"
                    class="btn {{ Route::currentRouteName() === $menu->menu_link ? 'btn-info' : 'btn-secondary text-white' }}">
                    {{ $menu->menu_name }}
                </a>
            @endif
        @endforeach
        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button"
                class="btn dropdown-toggle {{ in_array(Route::currentRouteName(), $menus->where('more_link', 'yes')->pluck('menu_link')->toArray()) ? 'btn-info' : 'btn-secondary text-white' }}"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                More
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                @foreach ($menus as $menu)
                    @if ($menu->more_link === 'yes')
                        <a class="dropdown-item {{ Route::currentRouteName() === $menu->menu_link ? 'btn-info' : 'text-info' }}"
                            href="{{ route($menu->menu_link) }}">
                            {{ $menu->menu_name }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
