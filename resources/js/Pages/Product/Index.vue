<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Manage Products
                </h2>

                <Link 
                    :href="route('product.create')" 
                    class="px-3
                            py-2.5
                            text-sm 
                            font-medium 
                            text-center 
                            text-white 
                            bg-blue-700 
                            rounded-lg 
                            hover:bg-blue-800 
                            focus:ring-4 
                            focus:outline-none 
                            focus:ring-blue-300"
                    >
                    New Product
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-end pb-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input type="text" id="table-search" @keydown.enter="handleSearch" :value="props.query.search" class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for items">
                    </div>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3" width="5" >
                                    No.
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <Sortable label="Product name" name="name" />
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Category
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <Sortable label="Price" name="price" />
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <Sortable label="Weight" name="weight" />
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in products.data" :key="product.id"  class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    {{ products.meta.from + index }}
                                </td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ product.name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ product.category.name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ product.price_formatted }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ product.weight }}
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <Link :href="route('product.show', product.id)" class="font-medium text-gray-600 hover:underline">Show</Link>
                                    <Link :href="route('product.edit', product.id)" class="font-medium text-blue-600 hover:underline">Edit</Link>
                                    <a href="#" class="font-medium text-red-600 hover:underline" @click.prevent="deleteRow(product.id)">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <Pagination :meta="products.meta"/>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import Sortable from '@/Components/Sortable.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const deleteRow = (id) => {
    if (window.confirm("Are you sure?")) {
        router.delete(route('product.destroy', id), {
            preserveScroll: true
        })
    }
}

const props = defineProps({
    products: {
        type: Object,
        required: true
    },
    query: {
        type: Object,
        default: () => ({
            search: ''
        })
    }
})

const handleSearch = (event) => {
    router.get(route('product.index'), {
        search: event.target.value
    })
}


</script>
