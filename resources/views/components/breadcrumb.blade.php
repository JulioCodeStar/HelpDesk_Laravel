@props(['items' => []])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        @foreach($items as $item)
            @if(!$loop->last)
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] ?? '#' }}">
                        @isset($item['icon'])<i class="{{ $item['icon'] }}"></i> @endisset{{ $item['label'] }}
                    </a>
                </li>
            @else
                <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
