<script setup>
    import { computed, ref, watch } from 'vue'
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3'
    import debounce from 'lodash/debounce';
import Modal from '@/Components/Modal.vue';

    // Define the props
    const props = defineProps({
        books: {
            type: Object,
        },
        filters: {
            type: Object,
        },
    })

    
    // Define the reactive properties
    const isModalVisible = ref(false);
    const selectedBook = ref({});
    const sortBy = ref('name');
    const sortDir = ref('asc');
    const search = ref(props.filters.search);

    // Define the debounced update function
    const updateBooks = debounce((params) => {
        router.get('/books', params, {
            preserveState: true,
            replace: true
        });
    }, 300);

    // Using a computed property to watch multiple values
    const combinedParams = computed(() => ({
        search: search.value,
        sortBy: sortBy.value,
        sortDir: sortDir.value,
    }));

    // Watch the computed property
    watch(combinedParams, (newParams) => {
        updateBooks(newParams);
    });

    const sort = (field) => {
        sortBy.value = field
        sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc'
    }

    const delete_book = (id) => {
        router.delete(`/books/${id}`, {
            onBefore: () => confirm('Are you sure you want to delete this book?'),
        })
    }
    const show_book = (book) => {
      selectedBook.value = book;
      isModalVisible.value = true;
    }

    const closeModal = () => {
        isModalVisible.value = false;
    };

    // const exportFile = () => {
    //     router.get(route('books.export'))
    // }
</script>

<template>
    <Head title="Books" />

    <GuestLayout>
        <div>
            <div class="flex justify-between gap-2 mb-4">
                <input v-model="search" type="text" name="search" id="search" class="col-span-2 h-10 border border-gray-300 mt-1 rounded px-2 w-72" placeholder="Search..." />

                <div class="flex gap-2">
                    <a
                        class="h-8 bg-green-500 hover:bg-green-700 text-white p-2 rounded text-sm"
                        type="button"
                        href="/books/export"
                        download
                    >
                    Export
                    </a>
                    <Link title="Add Book" :href="route('books.create')" class="h-8 bg-green-500 hover:bg-green-700 text-white p-2 rounded text-sm">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="14" width="14" viewBox="0 0 448 512">
                            <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/>
                        </svg> -->
                        Add book
                    </Link>
                </div>
            </div>
            <div class="shadow overflow-hidden border-b sm:rounded-lg">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="">
                                <table class="min-w-full divide-y divide-gray-200 w-full">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="cursor-pointer px-6 py-3 bg-gray-100 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                            </th>
                                            <th @click="sort('name')" scope="col" class="cursor-pointer px-6 py-3 bg-gray-100 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                                Book Name {{ sortBy === 'name' ? (sortDir === 'asc' ? '▲' : '▼') : '' }}
                                            </th>
                                            <th @click="sort('author')" scope="col" class="cursor-pointer px-6 py-3 bg-gray-100 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                                Author {{ sortBy === 'author' ? (sortDir === 'asc' ? '▲' : '▼') : '' }}
                                            </th>
                                            <th scope="col" width="200" class="px-6 py-3 bg-gray-100">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="book in books" :key="book.id">
                                            <td>
                                                <img :src="book.cover" :alt="book.name" class="h-10 w-10">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ book.name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-wrap text-sm text-gray-900 truncate max-w-40">
                                                {{ book.author }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button @click.prevent="show_book(book)" class="text-blue-600 hover:text-blue-900 mb-2 mr-2">View</button>
                                                <Link :href="route('books.edit', book.id)" class="text-indigo-600 hover:text-indigo-900 mb-2 mr-2">Edit</Link>
                                                <button @click.prevent="delete_book(book.id)" class="cursor-pointer text-red-600 hover:text-red-900 mb-2 mr-2">Delete</button>
                                            </td>
                                        </tr>
                                        <tr v-if="books.length <= 0">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                No records available.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <Modal :show="isModalVisible" :onClose="closeModal" :book="selectedBook">
            aaaaaaaaaaaaaa
            </Modal>
        </div>
    </GuestLayout>
</template>
