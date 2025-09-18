<template>
    <li class="pe-2 border-b-[1px] pb-1 last:border-none">
        <div class="transition duration-300 hover:bg-gray-50 rounded-lg">
            <header
                class="head grid grid-cols-[auto,auto] gap-4 h-[100%] items-center
                     justify-between cursor-pointer text-base select-none p-1"
                @click.stop="settingsStore.openSubmenu(child, submenu)"
            >
                <div class="right grid grid-cols-[auto,auto] justify-start items-center">
                    <div class="grid items-center justify-start">
                        <input @click.stop="settingsStore.setCheckedItem($event, child)"
                               type="checkbox" class="input-check input-check-sm inline-block me-2">
                    </div>
                    <div>
                        <h5 class="text-[.85rem] block">{{ child.title }}</h5>
                        <span class="text-[.75rem] opacity-50 block">{{ child.url }}</span>
                    </div>
                </div>
                <div class="left pe-2 grid grid-cols-[auto,auto,auto] justify-start gap-1 items-center">
                    <div
                        class="grid items-center justify-center p-1.5 cursor-pointer
                            rounded transition duration-150 hover:bg-[#F1FAFF] group"
                        @click.stop="settingsStore.openSubmenuModalEdit(child)"
                    >
                        <SvgComponent name="pencil" size=".8rem" color="#009ef7"
                                      class="transition duration-150 group-hover:fill-[#009ef7]"/>
                    </div>

                    <div class="no-bg-svg-btn-red"
                         @click.stop="settingsStore.deleteSubmenu(submenu.submenus, childIndex)">
                        <SvgComponent name="fi-rr-cross" size=".8rem" color="#F1416C"/>
                    </div>

                    <div class="">
                        <SvgComponent
                            name="fi-rr-angle-left"
                            size=".8rem"
                            class="transition-color duration-300 ease-in-out fill-[#5E6278] inline-block"
                            :style="settingsStore.isSubmenuOpened(child)? {'transform':'rotate(-90deg)'}:{}"
                        />
                    </div>
                </div>
            </header>

            <!-- submenus of submenus are below -->
            <transition name="fadeHeight">
                <div class="ps-10 space-y-2" v-if="settingsStore.isSubmenuOpened(child)">
                    <ul class="pt-2">
                        <li
                            v-for="(grandchild, grandchildIndex) in child.submenus"
                            class="grid grid-cols-[auto,auto] items-center justify-between pe-2 border-b-[1px] pb-1 last:border-none"
                        >
                            <div class="right">
                                <h5 class="text-[.8rem] block">{{ grandchild.title }}</h5>
                                <span class="text-[.7rem] opacity-50 block">{{ grandchild.url }}</span>
                            </div>
                            <div class="left grid grid-cols-[auto,auto] justify-start gap-1 items-center">
                                <div class="grid items-center justify-center p-1.5
                                                        cursor-pointer rounded transition duration-150
                                                        hover:bg-[#F1FAFF] group"
                                     @click.stop="settingsStore.openSubmenuModalEdit(grandchild)"
                                >
                                    <SvgComponent name="pencil" size=".8rem" color="#009ef7"
                                                  class="transition duration-150 group-hover:fill-[#009ef7]"/>
                                </div>

                                <div
                                    class="no-bg-svg-btn-red"
                                    @click.stop="settingsStore.deleteSubmenu(child.submenus, grandchildIndex)"
                                >
                                    <SvgComponent name="fi-rr-cross" size=".8rem" color="#F1416C"/>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </transition>
        </div>
    </li>
</template>

<script>
import SvgComponent from "@/Components/admin/Widgets/SvgComponent.vue";
import {useMenuSettingsStore} from "@/Stores/admin/settings/MenuSettingsStore";

export default {
    components: {SvgComponent},
    props: {
        submenu: Object,
        submenuIndex: Number,
        child: Object,
        childIndex: Number,
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
