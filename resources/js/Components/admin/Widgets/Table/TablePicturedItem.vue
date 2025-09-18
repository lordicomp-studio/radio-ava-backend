<template>
    <div class="userItem">
        <div class="user grid grid-cols-[auto,auto] justify-start gap-2">
            <figure class="h-[50px] w-[50px] rounded-full overflow-hidden">
                <img
                    :src="pictureUrl ? `/storage${pictureUrl}` : '/Images/profile default.png'"
                    class="object-cover w-[100%] h-[100%]"
                    alt=""
                >
            </figure>
            <div class="info grid grid-cols-[auto] items-center">
                <InertiaLink :href="link"
                             class="text-gray-900 transition-colors duration-300 hover:text-primary twoLine">
                    {{text}}</InertiaLink>
            </div>
        </div>
    </div>
</template>

<script>
import {defineComponent, onMounted, onUpdated, ref} from 'vue'

export default defineComponent({
    name: "TablePicturedItem",
    props: {
        pictureUrl: String,
        text: String,
        link: String
    },
})
</script>

<style scoped>
.twoLine{
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
