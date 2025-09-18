<template>
    <div class="simpleTextInput">
        <h4 class="text-sm mb-2 text-[#3f4254] text-start">
            {{label}}
            <span v-if="isEssential" class="text-danger">*</span>
        </h4>
        <input v-model="input" type="text" class="input-text w-full !p-2" :placeholder="`${label}...`">
        <p v-if="errors[errorKeyName]" class="text-xs mt-2 text-danger font-light text-start">{{ errors[errorKeyName][0] }}</p>
<!--        <p v-else class="text-xs mt-2 text-[#a1a5b7] font-light">{{label}} را وارد کنید.</p>-->
    </div>
</template>

<script>
import {ref, watch} from "vue";

export default {
    props:{
        label: String,
        isEssential: {
            type: Boolean,
            default: false,
        },
        modelValue: String,
        errors: Object,
        errorKeyName: String,
    },
    setup(props, context){
        const input = ref(props.modelValue);

        watch(input, ()=>{
            context.emit('update:modelValue', input.value);
        });

        watch(() => props.modelValue, ()=>{
            input.value = props.modelValue;
        })

        return {
            input,
        }
    },
}
</script>

<style scoped>

</style>
