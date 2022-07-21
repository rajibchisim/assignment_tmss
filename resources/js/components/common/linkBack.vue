<template>
    <a @click.prevent="$router.go(-1)" class="absolute inline-block p-2 text-gray-400 cursor-pointer top-4 left-4 hover:text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
        </svg>
    </a>
</template>
