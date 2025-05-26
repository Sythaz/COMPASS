<section class="content-header">
    <div class="row page-titles mx-0 pb-0">
        <div class="col p-md-0 mr-2">
            <h1>@yield('page-title', '')</h1>
            <p class="text-muted mb-0">@yield('page-description', '')</p>
        </div>
        <div class="col p-md-0 ml-2">
            <ol class="breadcrumb">
                {{-- <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li> --}}
                @foreach ($breadcrumb->list as $key => $value)
                    @if ($key == count($breadcrumb->list) - 1)
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $value }}</a>
                        </li>
                    @else
                        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ $value }}</a></li>
                    @endif
                @endforeach
            </ol>
        </div>
    </div>
</section>
