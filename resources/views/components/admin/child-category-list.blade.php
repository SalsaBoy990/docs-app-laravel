<li>
    <div class="{{ $selectedCategory->id === $childCategory->id ? 'active-category' : '' }}">

        <div class="flex padding-0-5">
            @if (count($childCategory->categories) > 0)
                <span class="caret caret-down"></span>
            @endif
            <h3 class="fs-16 margin-top-bottom-0">
                <a href="{{ route('category.selected', $childCategory->id)}}">
                    {{ $childCategory->name }}
                </a>
            </h3>
        </div>

    </div>


    <ul class="no-bullets padding-left-1-5 margin-top-0 margin-bottom-0 padding-bottom-0-5 padding-right-0 nested active">
        @if (count($childCategory->categories) > 0)
            @foreach ($childCategory->categories as $childCategory)
                <x-child-category-list :childCategory="$childCategory"
                                       :selectedCategory="$selectedCategory"></x-child-category-list>
            @endforeach
        @endif
    </ul>
</li>

