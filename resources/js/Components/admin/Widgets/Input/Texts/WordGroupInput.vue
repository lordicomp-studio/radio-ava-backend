<template>
    <div class="">
        <div class="input">
            <input type="text" v-model="inputText" @keyup.enter="addWordMethod"
                   class="input-text w-[100%]" :placeholder="placeHolder">
            <button
                @click="addWordMethod"
                class="btn btn-primary btn-active-primary ltr:-ml-[3.76rem] rtl:-mr-[3.76rem]
                 z-[5] absolute ltr:rounded-l-none rtl:rounded-r-none !py-[.7rem]"
            >
                <SvgComponent class="" name="fi-rr-add" size="1.2rem" color="#ccc"></SvgComponent>
            </button>
        </div>


        <ul class="list pt-3 flex flex-wrap gap-x-4 gap-y-3">
            <li v-for="(item, index) in modelValue" class="">
                <span class="bg-secondary flex items-center text-xs p-2 text-[#5e6278] text-center rounded">
                    <i class="ml-2 cursor-pointer" @click.stop="removeWordMethod(index)">
                        <SvgComponent name="fi-rr-cross-circle" size=".85rem" color="#A1A5B7"></SvgComponent>
                    </i>
                    {{item}}
                </span>
            </li>
        </ul>
    </div>
</template>

<script>
import {computed, onMounted, onUpdated, ref} from "vue";
import SvgComponent from "../../SvgComponent.vue";

export default {
    name: "WordGroupInput",
    components: {SvgComponent},
    props: {
        modelValue: Array,
        placeHolder: '',
    },
    setup(props, context){
        const wordsArray = ref(props.modelValue);
        const inputText = ref('');

        const addWordMethod = ()=>{
            wordsArray.value.push(inputText.value);
            inputText.value = '';
            context.emit('update:modelValue', JSON.parse(JSON.stringify(wordsArray.value)));
        }

        const removeWordMethod = (index)=>{
            wordsArray.value.splice(index, 1);
            context.emit('update:modelValue', JSON.parse(JSON.stringify(wordsArray.value)));
        };

        return{
            inputText,
            wordsArray,
            addWordMethod,
            removeWordMethod,
        }
    },
}
</script>

<style scoped>

</style>
