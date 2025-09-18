<template>
    <section class="tracksHandler">
        <header class="flex justify-between">
            <h3 class="text-md text-black">آهنگ ها</h3>
            <button
                class="btn btn-light-primary btn-light-active-primary"
                @click="addDetailMethod"
            >
                افزودن آهنگ
                <SvgComponent name="fi-rr-add" size="1.1rem" color="#ccc"/>
            </button>
        </header>

        <ul id="detailsList">
            <li
                v-for="(track, index) in store.newData.tracks"
                :key="index"
                class="pb-4 pt-2 border-b-2 last:border-none"
            >
                <div class="grid grid-cols-[1fr,auto] items-center justify-between gap-2">
                    <div class="grid">
                        <label class="text-sm text-[#3f4254] pr-2">آهنگ</label>
                        <ItemSelector v-model="store.newData.tracks[index]" searchLink="/admin/tracks/indexDataApi"/>
                    </div>

                    <div class="hover:cursor-pointer h-[100%] grid items-end pb-1.5" @click="store.newData.tracks.splice(index, 1)">
                        <SvgComponent name="fi-rr-cross-circle" size="1.4rem" class="fill-red-500"/>
                    </div>
                </div>
            </li>
        </ul>
    </section>
</template>

<script>
import SvgComponent from "@/Components/admin/Widgets/SvgComponent.vue";
import ItemSelector from "@/Components/admin/Widgets/Input/ItemSelector.vue";

export default {
    name: "tracksHandler",
    components: {ItemSelector, SvgComponent},
    props:{
        store: Object,
    },
    setup(props){
        const addDetailMethod = ()=>{
            if (!props.store.newData.tracks){
                props.store.newData.tracks = [];
            }

            props.store.newData.tracks.unshift({
                key: '',
                value: '',
            });
        }

        return {
            addDetailMethod,
        }
    },
}
</script>

<style scoped>
    input {
        @apply p-1.5 outline-none transition-all duration-150 rounded-lg text-sm
        font-medium placeholder-[#A1A5B7] text-[#5E6278]
        border-none bg-[#fafafa] focus:bg-[#fafafa] focus:shadow-[0_0_2px_0px_rgba(0,0,0,0.35)]
        h-max focus:ring-0 ring-0;
    }

    textarea {
        @apply p-2 outline-none transition-colors duration-150 rounded-lg text-sm
        font-medium placeholder-[#A1A5B7] text-[#5E6278]
        border-none bg-[#fafafa] focus:bg-[#fafafa] focus:shadow-[0_0_2px_0px_rgba(0,0,0,0.35)]
        focus:ring-0 ring-0 overflow-y-hidden;
    }

    textarea {
        resize: none;
        overflow: hidden;
        min-height: 35px;
        height: 35px;
        /*max-height: 100px;*/
    }
</style>
