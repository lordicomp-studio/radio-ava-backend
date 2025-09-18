<template>
    <section class="documents">
        <div class="tabNames p-3 bg-gray-300">
            <button class="btn btn-light-primary btn-light-active-primary btn-hover-rise inline-block mx-2" @click="currentTab='buttons'">Buttons</button>
            <button class="btn btn-light-primary btn-light-active-primary btn-hover-rise inline-block mx-2" @click="currentTab='inputs'">Inputs</button>
            <button class="btn btn-light-primary btn-light-active-primary btn-hover-rise inline-block mx-2" @click="currentTab='modals'">Modal</button>
            <button class="btn btn-light-primary btn-light-active-primary btn-hover-rise inline-block mx-2" @click="currentTab='selects'">Selects</button>
            <button class="btn btn-light-primary btn-light-active-primary btn-hover-rise inline-block mx-2" @click="currentTab='drawerDocument'">Drawer</button>
        </div>
        <div class="tabs">
            <component :is="currentTab"></component>
        </div>
    </section>
</template>

<script>
import {onBeforeMount, ref} from 'vue';
import buttons from './../../Components/admin/documents/Buttons.vue';
import AdminLayout from "../../Layouts/AdminLayout.vue";
import inputs from "../../Components/admin/documents/Inputs.vue";
import modals from "../../Components/admin/documents/Modals.vue";
import selects from "../../Components/admin/documents/Selects.vue";
import drawerDocument from "../../Components/admin/documents/DrawerDocument.vue";
import {useHeaderStore} from "../../Stores/admin/HeaderStore";

export default {
    name: "Documents",
    props: [],
    layout: AdminLayout,
    components:{
        modals,
        selects,
        inputs,
        buttons,
        drawerDocument,
    },
    setup(){
        const currentTab = ref('selects');
        const headerStore = useHeaderStore();

        onBeforeMount(()=>{
            headerStore.title = 'مستندات';
        })

        return {
            currentTab,
        };
    },
}
</script>

<style scoped>

</style>
