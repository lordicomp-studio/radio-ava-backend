<template>
    <button class="btn btn-primary" @click="openDrawer">{{btnText}}</button>
    <transition name="curtainDrop">
        <div class="curtain" v-if="isOpen" @click="openDrawer"></div>
    </transition>

    <transition name="drawerOpen">
        <section class="drawer" v-if="isOpen">
            <header class="grid grid-cols-[auto,auto] justify-between
                        pr-[1.8rem] pl-[1rem] py-[1rem] border-b w-[500px]">
                <strong class="text-[1.1rem] leading-[35px]">{{drawerHeader}}</strong>
                <div class="grid items-center justify-center w-[35px] h-[35px]
                        cursor-pointer rounded transition duration-150
                        hover:bg-[#F1FAFF] group" @click="openDrawer">
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"
                                  class="transition duration-150 group-hover:fill-[#009ef7]"></SvgComponent>
                </div>
            </header>
            <main class="text-sm text-gray-500 w-[500px] max-h-[90.5vh] overflow-y-auto">
                <div class="py-[26px] px-[30px]">
                    <slot></slot>
                </div>
            </main>
        </section>
    </transition>
</template>

<script setup>
import {ref} from "vue";
import SvgComponent from "./SvgComponent.vue";

    const isOpen = ref(false);

    const props = defineProps({
        btnText: String,
        drawerHeader: String,
    })

    const openDrawer = () =>{
        isOpen.value = !isOpen.value;
    };

</script>

<style scoped>
    section.drawer{
        @apply bg-white
        fixed left-0 top-0 z-[100]
        h-screen w-[500px]
    }

    div.curtain{
        @apply bg-[rgba(0,0,0,.2)]
        fixed right-0 top-0 left-0 bottom-0 z-[99]
        h-screen w-screen opacity-50
    }

    .drawerOpen-enter-active, .drawerOpen-leave-active {
        transition: all .4s ease-in-out!important;
        max-width: 500px;
    }
    .drawerOpen-enter-from, .drawerOpen-leave-to {
        max-width: 0;
    }

    .curtainDrop-enter-active, .curtainDrop-leave-active {
        transition: all .4s ease-in-out!important;
    }
    .curtainDrop-enter-from, .curtainDrop-leave-to {
        opacity: 0!important;
    }
</style>
