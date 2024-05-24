<script setup>
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import { Link, useForm, Head, router } from '@inertiajs/vue3';
    
    // Define the props
    const props = defineProps({
        errors: {
            type: Object,
        },
    });

    const form = useForm({
        books: null
    })

    function submit() {
        router.post(route('books.import'), form , {
            forceFormData: true,
            onSuccess: () => {
                form.reset()
            },
        })
    }
</script>
<template>
    <Head title="Import Book" />
    <GuestLayout>
        <template #back_button>
            <div class="flex justify-start gap-2">
               <Link :href="route('books.index')" title="Export" class="cursor-pointer bg-transparent hover:bg-gray-500 text-gray-700 font-semibold text-sm hover:text-white p-2 border border-gray-800 hover:border-transparent rounded">Go back</Link>
            </div>
        </template>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                Import Book
            </h2>
        </template>
        <form @submit.prevent="submit">
            <div class="my-4 w-full grid grid-cols-1 gap-4">
                <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 xl:p-8 ">
                    <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4 border-b border-gray-600 pb-4">Books</h3>
                    <!-- component -->
                    <div class="lg:col-span-2">
                        <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-6">
                            <div class="md:col-span-3">
                                <label for="books">Books File</label>
                                <input @input="form.books = $event.target.files[0]" type="file" id="books" class="h-10 border border-gray-300 mt-1 rounded p-2 w-full" value="" />
                                <span v-if="errors.books" class="text-red-500 text-xs">{{ errors.books }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
                    
            <div class="md:col-span-6 my-4 text-right">
                <div class="inline-flex items-end">
                    <button type="submit" :disabled="form.processing" class="w-32 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-2 rounded">Import</button>
                </div>
            </div>
        </form>

    </GuestLayout>
</template>