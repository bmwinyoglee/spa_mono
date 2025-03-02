<template>
    <Head title="Edit Product" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Edit Product
                </h2>

                <Link 
                    :href="route('product.index')" 
                    class="px-3
                        py-2.5
                        text-sm 
                        font-medium 
                        text-center 
                        text-white 
                        bg-blue-700 
                        rounded-md 
                        hover:bg-blue-800 
                        focus:ring-4 
                        focus:outline-none 
                        focus:ring-blue-300"
                    >
                   Manage Products
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center justify-center">
                    <div class="relative w-full max-w-2xl max-h-full">
                        <ProductForm :form="form" :categories="categories" @submit="update" >
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Update</button>
                            <Link :href="route('product.show', product.id)" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Cancel</Link>
                        </ProductForm>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProductForm from './ProductForm.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    categories: {
        type: Array,
        required: true
    },

    product: {
        type: Object,
        required: true
    }
})


const form = useForm({...props.product, category_id: props.product.category.id})

const update = () => {
    form.put(route('product.update', form.id), {
        onSuccess: () => form.reset()
    })
}
</script>
