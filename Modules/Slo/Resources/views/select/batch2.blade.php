<label for="course_id">Select Batch</label>
<select class="form-control " name="batch_id" id="batch_id" required>
    <option value="">Select Batch</option>
    @foreach($batches as $batches)
    <option value="{{$batches->batch_id}}">{{$batches->batch_name}}</option>
    @endforeach
</select>