<script setup>
</script>

<template>
    <div>
        <div class="flex overflow-hidden bg-white">
            <div id="main-content" class="h-full w-full relative overflow-y-auto">
                <main class="py-8 px-4 lg:px-8 mx-auto w-3/4">
                    
                    <div class="flex justify-start gap-2" v-if="$slots.back_button">
                        <slot name="back_button" />
                    </div>
                    <header class="mb-4" v-if="$slots.header">
                        <div class="mx-auto pt-2">
                            <slot name="header" />
                        </div>
                    </header>
                    <div v-if="$page.props.flash.success" class="success-panel my-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative message-alert" role="alert">
                        <!-- <strong class="font-bold">Holy smokes!</strong> -->
                        <span class="block sm:inline">{{$page.props.flash.success}}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="$page.props.flash.success = ''">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>

                    <div v-if="$page.props.flash.error" class="error-panel my-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative message-alert" role="alert">
                        <!-- <strong class="font-bold">Holy smokes!</strong> -->
                        <span class="block sm:inline">{{$page.props.flash.error}}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="$page.props.flash.error = ''">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
