<div class="row mt-3 mb-3">
    @php
    use App\Models\UserPermission;
    use App\Models\PermissionModel;

    $access_array = UserPermission::where('user_id', $commonUser->id)
    ->where('permission', 1)
    ->pluck('module_id')
    ->toArray();

    $parentModules = PermissionModel::where('parent_id', 0)
    ->orderBy('module_id', 'ASC')
    ->get();
    @endphp
    <div class="col-md-8">
        <form id="permissionsForm" action="{{ route('update.permissions') }}" method="POST">
            @csrf
            <div class="form-check form-check-inline">
                <input class="form-check-input info" type="radio" name="radio-solid-info" id="permissions_type_all"
                    value="all" {{ $commonUser->permissions_type
                == 'all' ? 'checked' : '' }}>
                <label class="form-check-label" for="permissions_type_all">All</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input info" type="radio" name="radio-solid-info" id="permissions_type_selected"
                    value="selected" {{ $commonUser->permissions_type == 'selected' ? 'checked' : '' }}>
                <label class="form-check-label" for="permissions_type_selected">Selected</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input info" type="radio" name="radio-solid-info" id="permissions_type_block"
                    value="block" {{ $commonUser->permissions_type == 'block' ? 'checked' : '' }}>
                <label class="form-check-label" for="permissions_type_block">Block</label>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    @foreach($parentModules as $parentModule)
                    @php

                    $childModules = PermissionModel::where('parent_id',
                    $parentModule->module_id)
                    ->orderBy('module_id', 'ASC')
                    ->get();
                    @endphp

                    <h6>{{ $loop->iteration }}: {{ $parentModule->module_name }}</h6>

                    @foreach($childModules as $childModule)
                    <div class="mb-2">
                        <label class="form-check-label" for="p_mod_{{ $childModule->module_id }}">
                            <input class="form-check-input permission-checkbox updatevalue" type="checkbox"
                                id="p_mod_{{ $childModule->module_id }}" name="{{ $childModule->module_id }}[]"
                                value="1" {{ in_array($childModule->module_id, $access_array) ? 'checked'
                            : '' }}>
                            {{ $childModule->module_name }}
                        </label>
                        <!-- Hidden input to ensure the value is always submitted -->
                        <input type="hidden" name="{{ $childModule->module_id }}[]" value="0">
                    </div>
                    @endforeach

                    <br><br>
                    @endforeach
                    <input type="hidden" name="user_id" value="{{ $commonUser->id }}">
                </div>
            </div>

          @if(auth()->user()->role == 'admin' || auth()->user()->role == 'superadmin')
                <button type="submit" onclick="document.getElementById('permissionsForm').submit();"  class="btn btn-primary">Save Permissions</button>
            @else
                <button type="button" class="btn btn-primary disabled">Save Permissions</button><br>
                <small class="text-white bg-danger px-2 mt-2">Dispatcher can't change the permission</small>
            @endif
        </form>
    </div>
</div>