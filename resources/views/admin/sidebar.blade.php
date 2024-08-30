


@php
 $sidebar=null;
   
@endphp





@php
use App\Models\NavMenuLeft;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Auth;

// Get the authenticated user ID
$userId = Auth::id();

// Fetch user permissions as an array of module IDs
$userPermissions = UserPermission::where('user_id', $userId)
    ->where('permission', 1)
    ->pluck('module_id')
    ->toArray();

// Fetch menus with their children and user permissions
$menus = NavMenuLeft::where('parent_id', 0)
    ->with(['children', 'children.userpermission'])
    ->orderBy('order_by', 'asc')
    ->get();
@endphp

<!-- Default Sidebar for other roles -->
@if (request('sidebar') == 'off')
    <!-- Do not display the header -->
@elseif ($sidebar == 'off')
    <!-- Do not display the header -->
@else

<aside class="left-sidebar">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav mt-2">
            <ul id="sidebarnav">
                @foreach ($menus as $menu)
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link {{ $menu->menu_class }} @if($menu->children->isNotEmpty()) has-arrow @endif" href="{{ $menu->menu_link === '#.' ? '#.' : route($menu->menu_link) }} " aria-expanded="false">
                        <i class="{{ $menu->menu_icon }}"></i><span class="hide-menu">{{ $menu->menu_name }}</span>
                    </a>
                    @if ($menu->children->isNotEmpty())
                    <ul aria-expanded="false" class="collapse first-level">
                        @php
                            // Filter children based on user permissions
                            $filteredChildren = $menu->children->filter(function($child) use ($userPermissions) {
                                return in_array($child->module_id, $userPermissions);
                            });
                           // dump($filteredChildren);
                        @endphp
                        @foreach ($filteredChildren as $child)
                            <li class="sidebar-item">
                                <a href="{{ $child->menu_link === '#.' ? '#.' : route($child->menu_link) }}" class="sidebar-link {{ $menu->menu_class }}">
                                    <i class="{{ $child->menu_icon }}"></i>
                                    <span class="hide-menu">{{ $child->menu_name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>
        </nav>
    </div>
</aside>



@endif