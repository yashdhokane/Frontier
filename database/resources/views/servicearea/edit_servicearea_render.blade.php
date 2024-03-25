<form action="{{ route('servicearea.update') }}" method="post" class="form-horizontal form-material"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="area_id" value="{{ $servicearea->area_id }}">
    <div class="form-group">


        <div class="row">
           {{--  <div class="col-md-6 mb-3">
                <div class="mparea">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d774440.6850054913!2d-75.16222184954196!3d40.69249737782041!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1704723153396!5m2!1sen!2sin"
                        width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>--}}
            <div class="col-md-12 mb-3">


                <div class="form-group  mb-3">
					<label for="area_name" class="control-label bold mb5">Name of the Service Area</label>
                    <input type="text" class="form-control" id="nametext" aria-describedby="name" name="area_name"
                        placeholder="Name" value="{{ $servicearea->area_name }}">

                </div>
                <div class="form-group  mb-3">
					<label for="area_description" class="control-label bold mb5">Description</label>
                    <input type="text" class="form-control" id="name1" aria-describedby="name" name="area_description"
                        placeholder="Description" value="{{ $servicearea->area_description }}">
				</div>
               <div class="form-group mb-3">
			   <label for="area_radius" class="control-label bold mb5">Radius</label>
    <select class="form-control" id="name2" aria-describedby="name" name="area_radius">
        <option value="1" {{ $servicearea->area_radius == 1 ? 'selected' : '' }}>1KM</option>
        <option value="2" {{ $servicearea->area_radius == 2 ? 'selected' : '' }}>2KM</option>
        <option value="3" {{ $servicearea->area_radius == 3 ? 'selected' : '' }}>3KM</option>
        <option value="4" {{ $servicearea->area_radius == 4 ? 'selected' : '' }}>4KM</option>
        <option value="5" {{ $servicearea->area_radius == 5 ? 'selected' : '' }}>5KM</option>
        <option value="10" {{ $servicearea->area_radius == 10 ? 'selected' : '' }}>10KM</option>
        <option value="20" {{ $servicearea->area_radius == 20 ? 'selected' : '' }}>20KM</option>
        <option value="50" {{ $servicearea->area_radius == 50 ? 'selected' : '' }}>50KM</option>
        <option value="100" {{ $servicearea->area_radius == 100 ? 'selected' : '' }}>100KM</option>
    </select>
</div>

                <div class="form-group  mb-3">
					<label for="area_latitude" class="control-label bold mb5">Latitude</label>
                    <input type="text" class="form-control" id="name3" aria-describedby="name"
                        value="{{ $servicearea->area_latitude }}" name="area_latitude" placeholder="Latitude">

                </div>
                <div class="form-group  mb-3">
					<label for="area_longitude" class="control-label bold mb5">Latitude</label>
                    <input type="text" class="form-control" id="name4" aria-describedby="name"
                        value="{{ $servicearea->area_longitude }}" name="area_longitude" placeholder="Longitude">

                </div>


            </div>
        </div>


    </div>

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info">
            Save
        </button>
         <button type="button" class="btn btn-info waves-effect" data-bs-dismiss="modal">
    Cancel
</button>

    </div>
</form>