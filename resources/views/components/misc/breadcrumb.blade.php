<ul class="flex items-center gap-x-2 opacity-70 mb-1">
    @foreach ($items as $item)
        <li>
            @if (!$loop->last)
                <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                <span>»</span>
            @else
                <span>{{ $item['title'] }}</span>
            @endif
        </li>
    @endforeach
</ul>
