@if ($cardPosition->ModuleList->module_code == 'schedule')
    <div class="col-md-12 col-lg-12 mb-3 box draggable-items1" id="box-1" data-original-index="1" tabindex="0"
        data-id="{{ $cardPosition->module_id }}">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $cardPosition->ModuleList->module_name }}</strong>
                </div>
                <div>
                    <button type="button" class="btn btn-link expand-btn">Expand</button>
                     <div style="float: right;">
                                                @if ($layout->added_by == auth()->user()->id)
                                                    <button class="btn btn-light mx-2 clearSection"
                                                        data-element-id="{{ $cardPosition->element_id }}"
                                                        data-id="{{ $cardPosition->module_id }}">X</button>
                                                @endif
                                            </div>
                </div>
            </div>
            <div class="card-body clearelement" data-id="{{ $cardPosition->module_id }}">
                @include('widgets.' . $cardPosition->ModuleList->module_code)
            </div>
        </div>
    </div>
@else
    <div class="col-md-4 mb-3 box draggable-items1" id="box-1" data-original-index="1" tabindex="0"
        data-id="{{ $cardPosition->module_id }}">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $cardPosition->ModuleList->module_name }}</strong>
                </div>
                <div>
                    <button type="button" class="btn btn-link expand-btn">Expand</button>
                     <div style="float: right;">
                                                @if ($layout->added_by == auth()->user()->id)
                                                    <button class="btn btn-light mx-2 clearSection"
                                                        data-element-id="{{ $cardPosition->element_id }}"
                                                        data-id="{{ $cardPosition->module_id }}">X</button>
                                                @endif
                                            </div>
                </div>
            </div>
            <div class="card-body clearelement" data-id="{{ $cardPosition->module_id }}">
                @include('widgets.' . $cardPosition->ModuleList->module_code)
            </div>
        </div>
    </div>
@endif
