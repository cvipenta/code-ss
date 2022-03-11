@php
    $categories = \App\Models\MedicalTestCategory::orderBy('name')->get();
@endphp
<ul>
    @foreach($categories as $category)
        <li>
            <x-medical-tests-category-link :category="$category"></x-medical-tests-category-link>
        </li>
    @endforeach
</ul>
