<template>
    <div class="justify-center grid grid-cols-auto p-8 bg-white w-fit m-auto rounded mt-4">
        <h1 class="p-3 mx-auto">Input components</h1>
        <h2 class="mt-4 mx-auto">text inputs</h2>
        <input type="text" class="input-text" placeholder="default input">
        <input type="text" class="input-text input-text-solid mt-4" placeholder="solid bg input">

        <h2 class="mt-8 mx-auto">iconed text inputs</h2>
        <label for="text-icon" class="input-icon-label">
            <i class="input-icon-icon">
                <SvgComponent name="fi-rr-alarm-clock" size="1.1rem" color="#a1a5b7"/>
            </i>
            <input type="text" class="input-icon-input" id="text-icon" placeholder="test">
        </label>

        <br>

        <div class="relative">
            <div class="absolute right-4 top-3 rotate-[90deg]">
                <SvgComponent name="fi-rr-search" size="1.2rem" color="#a1a5b7"/>
            </div>

            <input type="text" :placeholder="$t('tables.common.search')"
                   class="input-text input-text-solid pr-[2.8rem!important] text-[.9rem] font-normal">
        </div>

        <h2 class="mt-8 mx-auto">WordGroup text input</h2>
        <WordGroupInput class="" v-model="WordGroupInputModel" />

        <h2 class="mt-4 mx-auto">checkboxes</h2>
        <div>
            <input type="checkbox" class="input-check input-check-sm m-2 inline-block">
            <input type="checkbox" class="input-check m-2 inline-block">
            <input type="checkbox" class="input-check input-check-lg m-2 inline-block">
        </div>

        <h2 class="mt-4 mx-auto">radio boxes</h2>
        <div>
            <input type="radio" class="input-radio input-radio-sm m-2 inline-block">
            <input type="radio" class="input-radio m-2 inline-block">
            <input type="radio" class="input-radio input-radio-lg m-2 inline-block">
        </div>

        <h2 class="mt-4 mx-auto">text area</h2>
        <textarea class="textarea w-[20rem]"></textarea>

        <h2 class="mt-4 mx-auto">resizable text area</h2>
        <ResizableTextArea v-model="ResizableTextAreaModel"/>

    </div>
</template>

<script>
import SvgComponent from '../Widgets/SvgComponent.vue'
import WordGroupInput from "../Widgets/Input/Texts/WordGroupInput.vue";
import {ref} from "vue";
import ResizableTextArea from "../Widgets/Input/Texts/ResizableTextArea.vue";

export default {
    name: "Inputs",
    components: {ResizableTextArea, WordGroupInput, SvgComponent},
    setup(){
        const WordGroupInputModel = ref([]);
        const ResizableTextAreaModel = ref('some test text');

        return {
            WordGroupInputModel,
            ResizableTextAreaModel,
        }
    },
}
</script>

<style scoped>

</style>
