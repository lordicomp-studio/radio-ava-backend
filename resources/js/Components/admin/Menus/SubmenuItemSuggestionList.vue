<template>
    <section class="SubmenuItemSuggestionList mx-4 p-3 bg-white rounded-lg">
        <header class="p-2 lowercase">
            <h4 class="text-lg font-semibold">{{title}}</h4>
        </header>
        <main>
            <div class="input">
                <input
                    type="text" class="input-text input-text-solid w-[100%]"
                    v-model="searchText" :placeholder="`${$t('tables.common.search')} ${title.toLowerCase()}`"
                >
            </div>
            <ul class="pt-4 relative px-2 overflow-y-auto max-h-[10rem]">
                <Loader :isActive="showLoader" />
                <li class="border-b py-1"
                    v-for="item in dataList"
                    @click="$emit('sendData', item)"
                >
                    <p class="text-sm font-normal cursor-pointer">{{ item.name }}</p>
                </li>
            </ul>
        </main>
    </section>
</template>

<script>
import {useMenuSettingsStore} from "../../../Stores/admin/settings/MenuSettingsStore";
import {ref, watch} from "vue";
import Loader from "../../../Components/admin/Widgets/Loader.vue";

export default {
    name: "SubmenuItemSuggestionList",
    components: {Loader},
    props:{
        title: {
            type: String,
            required: true,
        },
        list: {
            type: [Object, Array],
            required: true,
        },
        searchLink: {
            type: String,
            required: true,
        }
    },
    emits: ['sendData'],
    setup(props, context){
        const store = useMenuSettingsStore();
        const dataList = ref(JSON.parse(JSON.stringify(props.list)));
        const showLoader = ref(false);

        const searchText = ref('');
        let timeout;
        watch(searchText, (value, oldValue, onCleanup)=>{
            clearTimeout(timeout);
            timeout = setTimeout(()=>{
                showLoader.value = true;

                axios.post(props.searchLink, {
                    filters:{ searchText: searchText.value }
                }).then((res)=>{
                    showLoader.value = false;

                    if (res.status === 200){
                        dataList.value = res.data.data;
                    }
                }).catch((error)=>{});
            }, 2000);
        });


        return {
            dataList,
            searchText,
            showLoader,
        }
    }

}
</script>

<style scoped>

</style>
