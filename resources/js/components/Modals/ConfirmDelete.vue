<template>
    <div class="flex items-center justify-center border rounded-lg" style="background: rgba(255, 255, 255, .9)">
        <div>
            <h2 class="mb-8 text-lg text-center">Are you sure?</h2>
            <div class="flex items-center justify-center gap-x-6">
                <button class="w-24 py-2 text-sm text-white bg-red-700 rounded-md hover:bg-red-500" @click="$emit('confirm')">Yes delete</button>
                <button class="w-24 py-2 text-sm text-white bg-gray-500 rounded-md hover:bg-gray-600" @click="$emit('cancel')">Cancel</button>
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
