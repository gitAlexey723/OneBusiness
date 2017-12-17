<div style="margin-bottom: 30px;">
  <form action="{{ route('branch_remittances.show', [$collection, 'corpID' => $company->corp_id]) }}"
    id="status-filter">
    <input type="hidden" name="corpID" value="{{ $company->corp_id }}"/>
    <div class="row">
      <div class="form-group">
        <label for="" class="control-label col-xs-2">
          CLEAR STATUS
        </label>
        <div class="col-xs-10">
          <label class="radio-inline" for="status_all">
            <input type="radio" name="status" id="status_all" value="all"
              {{ $queries['status'] == 'all' ? "checked" : "" }}>
            All
          </label>
        
          <label class="radio-inline" for="status_checked">
            <input type="radio" name="status" id="status_checked" value="1"
              {{ $queries['status'] == '1' ? "checked" : "" }}>
            Checked
          </label>

          <label class="radio-inline" for="status_unchecked">
            <input type="radio" name="status" id="status_unchecked" value="0"
              {{ $queries['status'] == '0' ? "checked" : "" }}>
            Unchecked
          </label>
          <div class="form-group">
            <label class="radio-inline" for="shortage_only" style="padding-left: 0px;">
              <input type="checkbox" name="shortage_only" id="shortage_only" value="1"
                {{ $queries['shortage_only'] == '1' ? "checked" : "" }}>
              Show Shortage only
            </label>

            <label class="radio-inline" for="remarks_only" style="padding-left: 0px;">
              <input type="checkbox" name="remarks_only" id="remarks_only" value="1"
                {{ $queries['remarks_only'] == '1' ? "checked" : "" }}>
              Show Remarks only
            </label>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<div class="table-responsive">
  <table class="table table-striped table-bordered table-remittances">
    <thead>
      <tr>
        <th class="text-center">BRANCH</th>
        <th class="text-center">DATE</th>
        <th class="text-center">SHIFT ID</th>
        <th class="text-center">SHIFT TIME</th>
        <th class="text-center">CASHIER NAME</th>
        <th class="text-center">RETAIL</th>
        <th class="text-center">SERVICES</th>
        <th class="text-center">GAMES</th>
        <th class="text-center">INTERNET</th>
        <th class="text-center">TOTAL SALES</th>
        <th class="text-center">TOTAL REMIT</th>
        <th class="text-center">CLR</th>
        <th class="text-center">WI</th>
        <th class="text-center">AS</th>
        <th class="text-center">SHORT</th>
        <th class="text-center">REMARKS</th>
        <th class="text-center">ACTION</th>
      </tr>
    </thead>
    <tbody>
      @php $totalShifts = 0 @endphp
      @foreach($collection->details()->get() as $detail)
        @foreach($detail->shifts($company->corp_id, $queries) as $branch => $shifts_by_date)
          @php $index_branch = $loop->index @endphp

          @php $count = 0 @endphp
          @foreach($shifts_by_date as $date => $shifts)
            @php $count += count($shifts); $totalShifts += count($shifts); @endphp
          @endforeach
          
          @foreach($shifts_by_date as $date => $shifts)
            @php $index = $loop->index @endphp
            @foreach($shifts as $shift)
              <tr data-branch="{{ $shift->branch->Branch }}" data-date="{{ $date }}">
                @if($index == 0 && $loop->index == 0)
                  <td class="col-branch" rowspan="{{$count}}">{{$shift->branch->ShortName}}</td>
                @endif
                @if($loop->index == 0 )
                  <td class="col-date" rowspan="{{count($shifts)}}">{{$date}}</td>
                @endif
                <td>{{ $shift->Shift_ID }}</td>
                <td>{{ date("h:i A", strtotime($shift->ShiftTime) ) }}</td>
                <td>{{ $shift->user ? $shift->user->UserName : "" }}</td>
                <td class="col-retail">
                  {{ $shift->remittance ? round($shift->remittance->Sales_TotalSales, 2) : "" }}
                </td>
                <td class="col-service">
                  {{ $shift->remittance ? round($shift->remittance->Serv_TotalSales, 2) : "" }}
                </td>
                <td class="col-rental">
                  {{ $shift->remittance ? round($shift->remittance->Games_TotalSales, 2) : "" }}
                </td>
                <td>
                  {{ $shift->remittance ? round($shift->remittance->Net_TotalSales, 2) : "" }}
                </td>
                <td class="col-sale">
                  {{ $shift->remittance ? round($shift->remittance->TotalSales, 2) : "" }}
                </td>
                <td class="col-remit">
                  {{ $shift->remittance ? round($shift->remittance->TotalRemit, 2) : "" }}
                </td>
                <td>
                  <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Sales_Checked == 1 ? "checked" : "") : "" }} onclick="return false;" >
                </td>
                <td>
                  <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Wrong_Input == 1 ? "checked" : "") : "" }} onclick="return false;" >
                </td>
                <td>
                  <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Adj_Short == 1 ? "checked" : "") : "" }} onclick="return false;"  >
                <td>
                  @if($shift->remittance->TotalSales < $shift->remittance->TotalRemit)
                    {{ number_format($shift->remittance->TotalSales - $shift->remittance->TotalRemit , 2) }}
                  @else
                    {{ number_format($shift->remittance->TotalRemit - $shift->remittance->TotalSales , 2) }}
                  @endif
                </td>
                <td>{{ $shift->remittance ? $shift->remittance->Notes : "" }}</td>
                <td>
                  <button type="button" class="btn btn-primary show_modal" data-shift-id="{{$shift->Shift_ID}}" 
                    data-toggle="modal" data-target="#Modal" data-corp="{{ $company->corp_id }}">
                    <i class="fa fa-pencil"></i>
                  </button>
                </td>
              </tr>
            @endforeach
          @endforeach
        @endforeach
      @endforeach
      @if($totalShifts == 0)
      <tr>
        <td colspan="17">
          No items
        </td>
      </tr>
      @endif
    </tbody>
  </table>
</div>

@section('pageJS')
<script type="text/javascript">
$(document).ready(function(){
  $('.table-remittances td').each(function(el, index) {
    if(parseInt($(this).text()) == 0) {
      $(this).css('color', 'red');
    }
  });

  $('#status-filter input[name="status"], \
    #status-filter input[name="shortage_only"], \
    #status-filter input[name="remarks_only"]').change(function(event) {
    $(this).parents('form').submit();
  });
});
</script>
@endsection