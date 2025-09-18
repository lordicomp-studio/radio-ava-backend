<template>
    <section class="menuSettings grid grid-cols-[1fr,25rem] gap-8">
        <div
            v-if="settingsStore.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[1001]"
        >
            <Loader class="rounded-lg" :isActive="settingsStore.showFullScreenLoader" :z-index="1001"/>
        </div>


        <div class="right grid grid-cols-[1fr] justify-start items-start gap-6 h-fit">
            <MenuItem
                v-for="(menu, menuIndex) in settingsStore.newMenus"
                :menu="menu"
                :menuIndex="menuIndex"
            />

            <button
                @click="settingsStore.openMenuModalCreate"
                class="btn btn-primary btn-active-primary w-fit"
            >
                <SvgComponent name="add-simple" size="1.1rem" color="#ccc" />
                {{ $t('tables.common.add') }} {{ $t('pages.MenusSettings') }}
            </button>
        </div>

        <div class="left border-r h-min relative rounded-lg overflow-hidden">
            <div class="curtain absolute w-[100%] h-[100%] bg-gray-500 opacity-[90%] z-[49]"
                v-if="settingsStore.checkedItem.item === null"
            >
                <span class="absolute top-24 right-0 left-0 mx-auto w-[14rem] text-center">
                    {{$t('forms.menuSettings.chooseAMenuOrSubmenu')}}
                </span>
            </div>
            <div class="content space-y-4">
                <SubmenuItemSuggestionList
                    :list="categories" :title="$t('pages.Categories')"
                    @send-data="settingsStore.addCategorySubmenuSuggestion"
                    searchLink="/admin/categories/indexDataApi"
                />
                <SubmenuItemSuggestionList
                    :list="tags" :title="$t('pages.Tags')"
                    @send-data="settingsStore.addTagSubmenuSuggestion"
                    searchLink="/admin/tags/indexDataApi"
                />
                <div class="button px-4 pb-2">
                    <button @click="settingsStore.openSubmenuModalCreate" class="btn btn-primary btn-active-primary grid grid-cols-[auto,auto] gap-2">
                        <SvgComponent name="fi-rr-add" size="1.2rem" color="#ccc"/>
                        {{ $t('forms.menuSettings.addCustomSubmenu') }}
                    </button>
                </div>
            </div>
        </div>

        <CreateMenuModal/>
        <CreateSubmenuModal/>
    </section>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import CreateMenuModal from "../../../Components/admin/Menus/CreateMenuModal.vue";
import CreateSubmenuModal from "../../../Components/admin/Menus/CreateSubmenuModal.vue";
import SubmenuItemSuggestionList from "../../../Components/admin/Menus/SubmenuItemSuggestionList.vue";
import SvgComponent from "../../../Components/admin/Widgets/SvgComponent.vue";
import {onBeforeMount, ref} from "vue";
import {useMenuSettingsStore} from "../../../Stores/admin/settings/MenuSettingsStore";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import Loader from "../../../Components/admin/Widgets/Loader.vue";
import TableActionsButton from "../../../Components/admin/Widgets/Table/TableActionsButton.vue";
import MenuItem from "@/Components/admin/Menus/items/MenuItem.vue";

export default {
    components: {
        MenuItem,
        TableActionsButton,
        Loader, SubmenuItemSuggestionList, CreateSubmenuModal, CreateMenuModal, SvgComponent},
    layout : AdminLayout,
    props:{
        menus: Object,
        categories: Object,
        tags: Object,
    },
    setup(props){
        const testJson = ref([
            {
                name: 'level 1: A',
                url: 'url-level1-A',
                submenus: [
                    {
                        title: 'level 2: A',
                        url: 'url-level2-A',
                        submenus: [
                            {
                                title: 'level 3: A',
                                url: 'url-level3-A'
                            },
                            {
                                title: 'level 3: B',
                                url: 'url-level3-B'
                            }
                        ],
                    },
                    {
                        title: 'level 2: B',
                        url: 'url-level2-B'
                    },
                    {
                        title: 'level 2: C',
                        url: 'url-level2-C'
                    },
                ],
            },
            {
                name: 'level 1: B',
                url: 'url-level1-B'
            },
            {
                name: 'level 1: C',
                url: 'url-level1-C'
            },
        ]);

        const settingsStore = useMenuSettingsStore(); // 'settingsStore' is in fact 'menuSettingsStore'
        const headerStore = useHeaderStore();


        onBeforeMount(()=>{
            settingsStore.setMenusData(props.menus);
            settingsStore.resetChanges(); // it defaults newMenus to old menus.

            headerStore.title = settingsStore.PageTitle;
        });

        return {
            settingsStore,
            testJson
        }
    },
}
</script>

<style>

</style>
