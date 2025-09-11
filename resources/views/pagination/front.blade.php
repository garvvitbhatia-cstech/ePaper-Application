@if ($paginator->hasPages())
  <div class="paggination-section wow fadeInUp">
  <nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
  	@if ($paginator->onFirstPage()) 
    <li class="page-item disabled">
      <a class="page-link" href="#" tabindex="-1"><</a>
    </li>
    @else
    <li class="page-item">
      <a class="page-link" href="{{ $paginator->previousPageUrl() }}" tabindex="-1"><</a>
    </li>
    @endif
    
    @foreach ($elements as $element)
    @if(is_string($element))
    <li class="page-item disabled"><span>{{ $element }}</span></li>
    @endif
    @if(is_array($element))
        @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <li class="page-item active">
                <span class="page-link">{{ $page }}</span>
            </li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
            @endif
        @endforeach
    @endif    
    @endforeach
    
    @if ($paginator->hasMorePages()) 
    <li class="page-item">
      <a class="page-link" href="{{ $paginator->nextPageUrl() }}">></a>
    </li>
	@else
    <li class="disabled page-item">
      <a class="page-link" href="#">></a>
    </li>
    @endif
    </ul>
    </nav>
  </div>
@endif