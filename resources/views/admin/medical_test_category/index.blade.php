<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Medical Test Category') }}
        </h2>

        <a href="{{ route('medical-test-categories.create') }}">
            <button
                class="bg-blue-900 hover:bg-blue-700 text-white font-semibold py-1 px-2 border border-blue-500 rounded">
                Add new
            </button>
        </a>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- start page content inner -->
                    <table class="min-w-full border-collapse block md:table">
                        <thead class="block md:table-header-group">
                        <tr class="border border-grey-500 md:border-none block md:table-row absolute -top-full md:top-auto -left-full md:left-auto  md:relative ">
                            <x-admin.table-th>#</x-admin.table-th>
                            <x-admin.table-th>Name</x-admin.table-th>
                            <x-admin.table-th>Slug</x-admin.table-th>
                            <x-admin.table-th>Created</x-admin.table-th>
                            <x-admin.table-th>Updated</x-admin.table-th>
                            <x-admin.table-th>Actions</x-admin.table-th>
                        </tr>
                        </thead>
                        <tbody class="block md:table-row-group">

                        @foreach($categories as $category)
                            <tr class="{{ $loop->even ? 'bg-gray-300' : 'bg-white' }} border border-grey-500 md:border-none block md:table-row">
                                <x-admin.table-td :column="'#'">{{$category->id}}</x-admin.table-td>
                                <x-admin.table-td :column="'Name'">{{$category->name}}</x-admin.table-td>
                                <x-admin.table-td :column="'Slug'"> {{$category->slug}}</x-admin.table-td>
                                <x-admin.table-td :column="'Created'">{{$category->created_at}}</x-admin.table-td>
                                <x-admin.table-td :column="'Updated'">{{$category->updated_at}}</x-admin.table-td>

                                <x-admin.table-td :column="'Actions'">
                                    <x-admin.button :type="'blue'" action="{{ route('medical-test-categories.edit', $category) }}">Edit</x-admin.button>
                                    <x-admin.button :type="'red'" formaction="{{ route('medical-test-categories.destroy', $category) }}">Delete</x-admin.button>
                                </x-admin.table-td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    <!-- end page content inner -->

                </div>
            </div>
        </div>

</x-app-layout>


