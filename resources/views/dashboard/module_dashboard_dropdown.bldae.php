<select name="module_id" class="form-select" required>
    @if ($variable->isEmpty() && $List->isEmpty())
        <option value="">All sections already exist</option>
    @else
        <option value="">Select to add section</option>
        @foreach ($variable as $value)
            <option value="{{ $value->module_id }}">
                {{ $value->ModuleList->module_name ?? null }}
            </option>
        @endforeach
        @foreach ($List as $item)
            <option value="{{ $item->module_id }}">
                {{ $item->module_name ?? null }}
            </option>
        @endforeach
    @endif
</select>
