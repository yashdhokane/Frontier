  @foreach ($products as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-2">
                        <div class="user-meta-info"><a
                                href="{{ route('product.edit', ['product_id' => $item->product_id]) }}">
                                <h6 class="user-name mb-0" data-name="name">
                                    {{ $item->product_name }}</h6>
                            </a></div>
                    </div>
                </div>
            </td>
            <td>{{ $item->product_code ?? null }}</td>
            <td>{{ $item->categoryProduct->category_name ?? null }}</td>
            <td>{{ $item->manufacturername->manufacturer_name ?? null }}</td>
            <td>{{ $item->product_short ?? null }}</td>
            <td>{{ $item->product_description ?? null }}</td>
            <td>{{ $item->base_price ?? '' }}</td>
            <td>{{ $item->tax ?? '' }}</td>
            <td>{{ $item->discount ?? '' }}</td>
            <td>{{ $item->total ?? '' }}</td>
            <td>{{ $item->stock ?? '' }}</td>
            <td>
                @if ($item->stock_status == 'in_stock')
                    In Stock
                @elseif($item->stock_status == 'out_of_stock')
                    Out of Stock
                @endif
            </td>
            <td>
                @if ($item->status == 'Publish')
                    Active
                @elseif($item->status == 'Draft')
                    Inactive
                @endif
            </td>
            <td>
                @foreach ($item->ProductAssign as $asign)
                 {{ $asign->Technician->name ?? 'Not Assigned' }},
                @endforeach
            </td>

        </tr>
    @endforeach