<ul class="flex items-center gap-x-2 opacity-70 mb-1">
    @foreach ($items as $item)
        <li>
            <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
            @if (!$loop->last)
                <span>»</span>
            @endif
        </li>
    @endforeach
</ul>
