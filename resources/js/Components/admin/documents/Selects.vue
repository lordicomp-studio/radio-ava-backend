<template>
    <section class="">
        <h1 class="w-max p-3 mx-auto">Select components</h1>

        <h2 class="mt-6 mx-auto w-fit">Simple Select</h2>
        <SelectBasic v-model="selectBasicData" :options-array="selectBasicOptions"/>

        <h2 class="mt-6 mx-auto w-fit">Multiple Select</h2>
        <SelectMultiple v-model="selectMultipleData" :options-array="selectMultipleDataOptions"/>

        <h2 class="mt-6 mx-auto w-fit">Item Selector(utilizing a given API endpoint)</h2>
        <ItemSelector v-model="ItemSelectorData" searchLink="/admin/users/indexDataApi"/>
    </section>
</template>

<script setup>
    import SelectBasic from "../Widgets/Input/Selects/SelectBasic.vue";
    import SelectMultiple from "../Widgets/Input/Selects/SelectMultiple.vue";
    import {reactive, ref, watch} from "vue";
    import ItemSelector from "@/Components/admin/Widgets/Input/ItemSelector.vue";

    const selectBasicData = ref('');
    const selectBasicOptions = ref({
        'test1': 'test 1 value',
        'test2': 'test 2 value',
        'test3': 'test 3 value',
        'test4': 'test 4 value',
    });

    const selectMultipleData = reactive([]);
    const selectMultipleDataOptions = reactive(['datum 1', 'datum 2', 'datum 3']); //we declared this variable because it has to be reactive
    const ItemSelectorData = ref(null);


    watch(selectBasicData, (newValue)=>{
        console.log(newValue);
    });
    watch(selectMultipleData, (newValue)=>{
        console.log(newValue);
    });
    watch(ItemSelectorData, (newValue)=>{
        console.log(newValue);
    });

</script>

<style scoped>

</style>
