<template>
    <svg :width="size" :height="size" :fill="color">
        <use :xlink:href="`#${name}`"></use>
    </svg>
</template>

<script>
export default {
    props:[
        'name',
        'size',
        'color',
    ],
    name: "SvgComponent"
}
</script>

<style scoped>

</style>
