<template>
    <article>
        <div class="transition duration-300 hover:bg-white rounded-lg">
            <header
                class="head grid grid-cols-[auto,auto] gap-4 h-[100%] items-center
                     justify-between cursor-pointer text-base select-none p-1"
                @click="settingsStore.openSubmenu(submenu)"
            >
                <div class="right grid grid-cols-[auto,auto] justify-start items-center">
                    <div class="grid items-center justify-start">
                        <input @click.stop="settingsStore.setCheckedItem($event, submenu)"
                               type="checkbox" class="input-check input-check-sm inline-block me-2">
                    </div>
                    <div>
                        <h5 class="text-[.9rem] block">{{ submenu.title }}</h5>
                        <span class="text-[.8rem] opacity-50 block">{{ submenu.url }}</span>
                    </div>
                </div>
                <div class="left pe-2 grid grid-cols-[auto,auto,auto] justify-start gap-1 items-center">
                    <div
                        class="grid items-center justify-center p-1.5 cursor-pointer
                            rounded transition duration-150 hover:bg-[#F1FAFF] group"
                        @click.stop="settingsStore.openSubmenuModalEdit(submenu)"
                    >
                        <SvgComponent name="pencil" size=".8rem" color="#009ef7"
                                      class="transition duration-150 group-hover:fill-[#009ef7]"/>
                    </div>

                    <div class="no-bg-svg-btn-red"
                         @click.stop="settingsStore.deleteSubmenu(parentMenu.submenus, submenuIndex)">
                        <SvgComponent name="fi-rr-cross" size=".8rem" color="#F1416C"/>
                    </div>

                    <div class="">
                        <SvgComponent
                            name="fi-rr-angle-left"
                            size=".8rem"
                            class="transition-color duration-300 ease-in-out fill-[#5E6278] inline-block"
                            :style="settingsStore.isSubmenuOpened(submenu)? {'transform':'rotate(-90deg)'}:{}"
                        />
                    </div>
                </div>
            </header>

            <!-- submenus of submenus are below -->
            <transition name="fadeHeight">
                <div class="ps-10 space-y-2" v-if="settingsStore.isSubmenuOpened(submenu)">
                    <ul class="pt-2">
                        <SubmenuChildItem
                            v-for="(child, childIndex) in submenu.submenus"
                            :submenu="submenu"
                            :submenuIndex="submenuIndex"
                            :child="child"
                            :childIndex="childIndex"
                        />
                    </ul>
                </div>
            </transition>
        </div>
    </article>
</template>

<script>
import SvgComponent from "@/Components/admin/Widgets/SvgComponent.vue";
import {useMenuSettingsStore} from "@/Stores/admin/settings/MenuSettingsStore";
import SubmenuChildItem from "@/Components/admin/Menus/items/SubmenuChildItem.vue";

export default {
    components: {SubmenuChildItem, SvgComponent},
    props: {
        parentMenu: Object,
        parentMenuIndex: Number,
        submenu: Object,
        submenuIndex: Number,
    },
    setup(props){
        const settingsStore = useMenuSettingsStore(); // 'settingsStore' is in fact 'menuSettingsStore'

        return {
            settingsStore,
        }
    }
}

</script>


<style scoped>

</style>
