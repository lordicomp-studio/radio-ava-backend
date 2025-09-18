<template >
    <section class="simpleNumbers bg-white p-4 rounded-lg">
        <div class="w-40 mb-2">
            <SelectBasic
                :optionsArray="['1 days', '3 days', '7 days', '1 month', '3 months', '6 months', '1 year']"
                :showSearch="false"
                v-model="testModel"
            />
        </div>
        <div class="grid grid-cols-[repeat(4,1fr)] gap-4">
            <SimpleNumber link=""/>
            <SimpleNumber link=""/>
            <SimpleNumber link=""/>
            <SimpleNumber link=""/>
        </div>
    </section>

    <section class="pieAndLineCharts grid grid-cols-[1fr,2fr] mt-4 gap-4">
        <div class="left bg-white rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">
                Browser Usage
            </h3>
            <PieChart />
        </div>

        <div class="right px-4 bg-white rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">
                Growth Summary
            </h3>
            <LineChart />
        </div>
    </section>
</template>

<script>
import AdminLayout from "../../Layouts/AdminLayout.vue";
import {onBeforeMount, ref} from "vue";
import {useHeaderStore} from "@/Stores/admin/HeaderStore";
import SimpleNumber from "@/Components/admin/Dashboard/SimpleNumber.vue";
import SelectBasic from "@/Components/admin/Widgets/Input/Selects/SelectBasic.vue";
import PieChart from "@/Components/admin/Dashboard/PieChart.vue";
import LineChart from "@/Components/admin/Dashboard/LineChart.vue";

export default {
    components: {LineChart, PieChart, SelectBasic, SimpleNumber},
    layout : AdminLayout,
    setup(){
        const headerStore = useHeaderStore();

        onBeforeMount(()=>{
            headerStore.title = "Dashboard";
        });

        const testModel = ref('1D');

        return {
            testModel,
        }
    },
}
</script>
