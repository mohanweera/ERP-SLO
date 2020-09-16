<select class="form-control myDropdown" name="batch_id" id="batch_id">
<option value="">Select Batch</option>
@foreach($batches as $batches)
<option value="{{$batches->batch_id}}">{{$batches->batch_name}}</option>
@endforeach
</select>