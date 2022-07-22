<template>
<div class="relative flex flex-col w-full min-w-0 mt-8 mb-6 break-words bg-white rounded-lg shadow-xl">
    <div class="h-[800px] flex justify-center items-center flex-col" style="max-height: 70vh;">
        <h1>Not found</h1>
        <div class="mt-8 text-center">
            <router-link :to="{ name: 'home' }" class="text-gray-500 hover:text-gray-700">Return to dashboard</router-link>
        </div>
    </div>
</div>
</template>

<script>
export default {

}
</script>

<style>

</style>
