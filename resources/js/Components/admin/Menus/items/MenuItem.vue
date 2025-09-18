<template>
    <div class="submenu bg-white rounded-lg hover:border-[#009ef7] border-[1px]">
        <header
            class="grid grid-cols-[auto,auto] justify-between items-center p-4 cursor-pointer"
            @click="settingsStore.openMenu(menu)"
        >
            <div class="right grid grid-cols-[auto,auto] items-center justify-start gap-2">
                <div class="checkbox p-2">
                    <input @click.stop="settingsStore.setCheckedItem($event, menu)"
                           type="checkbox" class="input-check inline-block">
                </div>
                <div class="text">
                    <h4 class="text-md">{{ menu.title }}</h4>
                    <span class="text-sm opacity-50">{{ menu.name }}</span>
                </div>
            </div>
            <div class="left grid grid-cols-[auto,auto] items-center justify-center gap-4">
                <TableActionsButton :minimal="true">
                    <span @click="settingsStore.openMenuModalEdit(menu)">{{$t('forms.menuSettings.editName')}}</span>
                    <span @click="settingsStore.askDeleteAffirmation(menu, false)">{{$t('tables.common.delete')}}</span>
                </TableActionsButton>

                <SvgComponent
                    name="fi-rr-angle-left"
                    size="1rem"
                    class="transition-color duration-300 ease-in-out"
                    :style="settingsStore.openedMenuId === menu.id? {'transform':'rotate(-90deg)'} : {}"
                />
            </div>
        </header>

<!--    submenus of menus are below -->
        <transition name="fadeHeightLong">
            <section
                v-show="settingsStore.openedMenuId === menu.id"
                class="mr-20 ml-10 overflow-hidden"
            >
                <div class="p-3 bg-[#F5F8FA] rounded-lg">
                    <SubmenuItem
                        v-for="(submenu, submenuIndex) in menu.submenus"
                        :parentMenu="menu"
                        :parentMenuIndex="menuIndex"
                        :submenu="submenu"
                        :submenuIndex="submenuIndex"
                    />
                </div>

                <div class="buttons mt-2 rtl:space-x-reverse space-x-2 pb-2">
                    <button class="btn btn-sm btn-outline btn-outline-primary"
                            @click="settingsStore.resetSubmenusButton(menuIndex)">{{$t('forms.common.revert')}}</button>
                    <button class="btn btn-sm btn-primary btn-active-primary"
                            @click="settingsStore.sendMenuData(menu, menuIndex)">{{$t('forms.common.save')}}</button>
                </div>
            </section>
        </transition>
    </div>
</template>

<script>
import {useMenuSettingsStore} from "@/Stores/admin/settings/MenuSettingsStore";
import SvgComponent from "@/Components/admin/Widgets/SvgComponent.vue";
import TableActionsButton from "@/Components/admin/Widgets/Table/TableActionsButton.vue";
import SubmenuItem from "@/Components/admin/Menus/items/SubmenuItem.vue";

export default {
    name: 'MenuItem',
    components: {SubmenuItem, TableActionsButton, SvgComponent},
    props: {
        menu: Object,
        menuIndex: Number,
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
