@php
$siteUrl = env('APP_URL');
@endphp
@if($records->count()>0)

    @foreach($records as $key => $row)

    <tr>
    <td>{{$row->id}}.</td>
    <td>@if($row->title != '')<img src="{{$siteUrl}}/public/admin/images/newspapers/{{$row->title}}" width="300px" />@endif</td>
    
    <td><input type="text" value="{{$row->ordering}}" class="form-control" max="2" style="width:60px" onblur="updateOrder('{{$row->id}}',this.value);" /></td>
    
    
    <td>{!! date('d M, Y h:i A',strtotime($row->created_at)) !!}</td>
    <td>
    <a href="{{ url('/admin/edit-newspaper-page',base64_encode($row->id)) }}" class="btn btn-sm btn-primary" title="Edit">
      <i class="bi bi-pencil"></i>
      </a>
    <a href="javascript:void(0);" onclick="deleteData('newspaper_images','{{ $row->id }}');" class="btn btn-sm btn-danger" title="Delete">
      <i class="bi bi-trash"></i></a>
    </td>
    </tr>

    @endforeach
    @else
    <tr>
        <td align="center" colspan="15">Record not found</td>
    </tr>
    @endif
