@if($records->count()>0)
@php
$siteUrl = env('APP_URL');
@endphp
@foreach($records as $key => $row)   
<tr>
   <td>
      <div class="d-flex align-items-center">
         {!! $row->id !!}
      </div>
   </td>
   <td>
      <div class="d-flex align-items-center">
         {!! $row->email !!}
      </div>
   </td>

   <td>
      @if($row->status == 1)
      <a href="javascript:void(0);" onclick="changeStatus('newsletters','{!!$row->id!!}','{!!$row->status!!}');" class="badge bg-success ">Active</a>
      @else
      <a href="javascript:void(0);" onclick="changeStatus('newsletters','{!!$row->id!!}','{!!$row->status!!}');" class="badge bg-danger">In-Active</a>
      @endif
   </td>
   <td>
      <span class="text-muted fw-bold text-muted d-block fs-7">{!! date('d M, Y h:i A',strtotime($row->created_at)) !!}</span>
   </td>
   <td>
      <a href="{{ url('/admin/edit-newsletter',base64_encode($row->id)) }}" class="btn btn-sm btn-primary" title="Edit">
      <i class="bi bi-pencil"></i>
      </a>
      <a href="javascript:void(0);" onclick="deleteData('newsletters','{{ $row->id }}');" class="btn btn-sm btn-danger" title="Delete">
      <i class="bi bi-trash"></i>
      </a>
   </td>
</tr>

@endforeach
@else
<tr>
   <td align="center" colspan="15">Record not found</td>
</tr>
@endif
<tr>
   <td align="center" colspan="15">
      <div id="pagination">{{ $records->appends(request()->except('page'))->links('vendor.pagination.custom') }}</div>
   </td>
</tr>
