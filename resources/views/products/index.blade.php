<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(auth()->user()->is_admin)
                        <a href="{{route('products.create')}}" class="mb-4 inline-flex items-center px-4 py-2 bg-orange-400">Add Product</a>
                    @endif
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Name</span>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Price</span>
                            </th>
                        </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                        @forelse($products as $product)
                            <tr class="bg-white">
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    {{ $product->price }}
                                </td>
                                @if(auth()->user()->is_admin)
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        <a href="{{route('products.edit', $product)}}" class="mb-4 inline-flex items-center px-4 py-2 bg-orange-400">Edit</a>
                                        <form action="{{route('products.destroy', $product)}}" method="post" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <x-primary-button onclick="return confirm('Are you sure?')" class="bg-red-600 text-white" >Delete</x-primary-button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <td colspan="2" class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                {{ __('No products found') }}
                            </td>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
