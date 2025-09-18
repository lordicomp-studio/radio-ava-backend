<template>
    <textarea class="textarea w-[100%] overflow-y-hidden"
              v-model="text"
              ref="textAreaDomElement"
              :placeholder="placeHolder"
              @input="textareaResizeMethod"/>
</template>

<script>
import {defineComponent, onMounted, onUpdated, ref, watch} from 'vue'

export default defineComponent({
    name: "ResizableTextArea",
    props:{
        modelValue: String,
        placeHolder: {
            type: String,
            default: 'متن خود را وارد کنید'
        },
    },
    setup(props, context){
        const text = ref(props.modelValue);

        const textareaResizeMethod = (e)=>{
            e.target.style.height = '5px';
            e.target.style.height = (e.target.scrollHeight) + "px";

            context.emit('update:modelValue', text.value)
        };

        onMounted(()=>{
            recalculateHeight();
        })

        onUpdated(()=>{
            recalculateHeight();
        })

        watch(()=> props.modelValue, ()=>{
            text.value = props.modelValue;
            recalculateHeight();
        })

        const textAreaDomElement = ref(null);
        function recalculateHeight(){
            const txHeight = 52;

            if (textAreaDomElement.value.value == '') {
                textAreaDomElement.value.setAttribute("style", "height:" + txHeight + "px;");
            } else {
                textAreaDomElement.value.setAttribute("style", "height:" + (textAreaDomElement.value.scrollHeight) + "px;");
            }
        }

        return {
            text,
            textareaResizeMethod,
            textAreaDomElement, // ref to dom component
        }
    }
})
</script>

<style scoped>

</style>
