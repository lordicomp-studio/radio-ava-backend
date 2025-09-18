<template>
    <div class="link group select-none cursor-pointer">
        <div @click="openMenuMethod"
             class="grid grid-cols-[1fr,auto] items-center justify-between
                    text-sidebar-text text-center h-10 group-hover:bg-sidebar-bgHover"
             :class="isOpenMethod()? 'bg-sidebar-bgHover' : ''">

            <!--         if it is multi link, there will be a <div> containing a <span> + 2 svg components.
                         if it is single link, there will be a <inertia-link>.   -->
            <div v-if="menu.length" class="grid grid-cols-[auto,1fr,auto] gap-4 h-[100%] items-center ps-6">
                <svg-component :name="svg" size="1.1rem"
                        class="transition-color duration-300 ease-in-out fill-sidebar-text group-hover:fill-sidebar-svgHover"
                       :class="isOpenMethod()? '!fill-sidebar-svgHover' : ''"/>
                <span class="font-light text-sm text-sidebar-text text-start
                             transition-color duration-300 ease-in-out group-hover:text-[#fff]"
                      :class="isOpenMethod()? '!text-[#fff]' : ''"
                >{{ name }}</span>
                <div class="pe-6">
                    <svg-component v-if="menu.length" name="fi-rr-angle-left" size=".6rem"
                                   class="transition-color duration-300 ease-in-out fill-sidebar-text group-hover:fill-[#fff]"
                                   :class="isOpenMethod()? '!fill-[#fff]' : ''"
                                   :style="isOpenMethod()? {'transform': 'rotate(-90deg)'} : {}"/>
                </div>
            </div>
            <inertia-link v-else :href="link"
                          class="grid grid-cols-[auto,auto] gap-4 h-[100%] items-center justify-start
                                font-light text-sm text-sidebar-text ps-6
                                transition-color duration-300 ease-in-out group-hover:text-[#fff]"
                          :class="isItemChosen(link)? '!text-[#fff] !bg-sidebar-bgHover' : '!bg-[#1e1e2d]'"
            >
                <svg-component :name="svg" size="1.1rem"
                               class="transition-color duration-300 ease-in-out fill-sidebar-text group-hover:fill-sidebar-svgHover"
                               :class="isItemChosen(link)? 'fill-sidebar-svgHover' : ''"
                />
                <span>{{ name }}</span>
            </inertia-link>
        </div>
        <transition name="fadeHeight">
            <div v-if="isOpenMethod()">
                <div v-for="(link,index) in menu" :key="index"
                     class="h-10 text-sidebar-text grid items-center group
                             hover:bg-sidebar-bgHover hover:text-[#fff]"
                     :class="isItemChosen(link.link)? 'bg-sidebar-bgHover !text-[#fff]' : ''"
                >
                    <inertia-link :href="link.link"
                       class="font-light text-sm h-[100%]
                              grid grid-cols-[auto,auto] items-center justify-start gap-4
                              before:h-1 before:w-1 before:bg-sidebar-text before:content-[''] before:rounded
                              hover:before:bg-sidebar-svgHover ps-11"
                       :class="isItemChosen(link.link)? '!before:bg-sidebar-svgHover' : ''"
                    >
                        <span>{{link.name}}</span>
                    </inertia-link>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import SvgComponent from "../../../Components/admin/Widgets/SvgComponent.vue";
import {onMounted, ref} from "vue";
import {useSidebarStore} from "../../../Stores/admin/SidebarStore";
import { router } from '@inertiajs/vue3'

export default {
    name: "SidebarItem",
    components: {SvgComponent},
    props:[
        'menu', 'name', 'link', 'svg',
    ],
    setup(props){
        const store = useSidebarStore();

        const openMenuMethod = ()=>{
            if (store.openedMenuName === props.name){
                store.openedMenuName = '';
            }else{
                store.openedMenuName = props.name;
            }
        };

        const isOpenMethod = ()=>{
            return props.name === store.openedMenuName;
        };

        onMounted(()=>{
            // for updating links in sidebar store when navigating
            router.on('navigate', (event) => {
                store.currentPageLink = event.detail.page.url;
                // console.log(`Navigated to ${event.detail.page.url}`)
            })
        });

        const isItemChosen = (itemLink)=>{
            return store.currentPageLink === itemLink;
        }

        return {
            openMenuMethod,
            isOpenMethod,
            store,
            isItemChosen,
        }
    },
}
</script>

<style scoped>
.s{

}
</style>
