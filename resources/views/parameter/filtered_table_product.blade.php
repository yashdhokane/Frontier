 @foreach ($products as $index => $item)
                              <tr>
                                  <td>{{ $index + 1 }}</td>
                                  <td>
                                      <div class="d-flex align-items-center">

                                          @if ($item->product_image)
                                              <img src="{{ asset('public/product_image/' . $item->product_image) }}"
                                                  alt="{{ $item->product_name }}" class="rounded-circle"
                                                  width="45" />
                                          @else
                                              <img src="{{ asset('public/images/default-part-image.png') }}"
                                                  alt="{{ $item->product_name }}" class="rounded-circle"
                                                  width="45" />
                                          @endif


                                          <div class="ms-2">
                                              <div class="user-meta-info"><a
                                                      href="{{ route('product.edit', ['product_id' => $item->product_id]) }}">
                                                      <h6 class="user-name mb-0" data-name="name">
                                                          {{ $item->product_name }}</h6>
                                                  </a></div>
                                          </div>
                                      </div>
                                  </td>
                                  <td>{{ $item->categoryProduct->category_name ?? null }}</td>
                                  <td>{{ $item->manufacturername->manufacturer_name ?? null }}</td>
                                  <td>${{ $item->base_price ?? '' }}</td>
                                  <td>
                                      @if ($item->status == 'Publish')
                                          Active
                                      @elseif($item->status == 'Draft')
                                          Inactive
                                      @endif
                                  </td>

                              </tr>
                          @endforeach