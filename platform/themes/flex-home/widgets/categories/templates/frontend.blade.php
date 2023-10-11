@if (is_plugin_active('blog'))
    <div class="boxright">
        <h5>Danh mục</h5>
        <ul class="listnews">
            @foreach(get_categories(['select' => ['categories.id', 'categories.name']]) as $category)
                <li><a href="{{ $category->url }}" class="text-dark">{{ $category->name }}</a></li>
            @endforeach
        </ul>
    </div>
@endif
