@props(['tag'])


<span @style(['border-color: ' . $tag->color . '20', 'background-color: ' . $tag->color . '20', 'color: ' . $tag->color]) class="text-sm font-medium px-2.5 py-0.5 rounded inline-flex justify-center items-center">
    {{ $tag->name }}
</span>
