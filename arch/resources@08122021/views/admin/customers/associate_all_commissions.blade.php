<div class="table-responsive">
    <table class="table table-bordered">
        <div class="text-center" style="background-color:#167bea; color: white;">&nbsp; <span id="getNnC"></span> </div>
        <thead>
            <tr>
                <th>SNo.</th>
                <th>Interest %</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if($commrates->count()>0)
            @foreach($commrates as $key => $comm)
                <input type="hidden" id="asso_name_n_code" value={{ $comm->associate->name}}>
                <tr>
                    <td>{{$key+1}}.</td>
                    <td>{{$comm->commission_percent}}</td>
                    <td>{{Carbon\Carbon::parse($comm->start_date)->format('d-m-Y')}}</td>
                    <td>{{$comm->end_date?Carbon\Carbon::parse($comm->end_date)->format('d-m-Y'):'Till Date'}}</td>
                    <td>
                        @if($comm->status)
                        <p class="text-success text-center bg-active">Active</p>
                        @else
                        <p class="text-danger text-center bg-inactive">Inactive</p>
                        @endif
                    </td>
                </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
