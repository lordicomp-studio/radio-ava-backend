<template>
    <section class="buttons">
        <h1 class="w-max p-3 mx-auto">Button components</h1>
        <h2 class="w-max p-3 mx-auto">Base style</h2>
        <button class="btn btn-white btn-active-white mb-2 mx-auto ml-4">white</button>
        <button class="btn btn-primary btn-active-primary mb-2 mx-auto ml-4">primary</button>
        <button class="btn btn-light btn-active-light-primary mb-2 mx-auto ml-4">light-primary</button>
        <button class="btn btn-light btn-active-light mb-2 mx-auto ml-4">light</button>
        <button class="btn btn-secondary btn-active-secondary mb-2 mx-auto ml-4">secondary</button>
        <button class="btn btn-success btn-active-success mb-2 mx-auto ml-4">success</button>
        <button class="btn btn-info btn-active-info mb-2 mx-auto ml-4">info</button>
        <button class="btn btn-warning btn-active-warning mb-2 mx-auto ml-4">warning</button>
        <button class="btn btn-danger btn-active-danger mb-2 mx-auto ml-4">danger</button>
        <button class="btn btn-dark btn-active-dark mb-2 mx-auto ml-4">dark</button>
        <h2 class="w-max p-3 mx-auto">hover effects</h2>
        <button class="btn btn-primary btn-hover-rise mb-2 mx-auto ml-4">rise</button>
        <button class="btn btn-danger btn-hover-scale mb-2 mx-auto ml-4">scale</button>
        <button class="btn btn-success btn-hover-rotate-start mb-2 mx-auto ml-4">rotate to start</button>
        <button class="btn btn-warning btn-hover-rotate-end mb-2 mx-auto ml-4">rotate to end</button>
        <h2 class="w-max p-3 mx-auto">Light style</h2>
        <button class="btn btn-light-primary btn-light-active-primary mb-2 mx-auto ml-4">primary</button>
        <button class="btn btn-light-success btn-light-active-success mb-2 mx-auto ml-4">success</button>
        <button class="btn btn-light-info btn-light-active-info mb-2 mx-auto ml-4">info</button>
        <button class="btn btn-light-warning btn-light-active-warning mb-2 mx-auto ml-4">warning</button>
        <button class="btn btn-light-danger btn-light-active-danger mb-2 mx-auto ml-4">danger</button>
        <button class="btn btn-light-dark btn-light-active-dark mb-2 mx-auto ml-4">dark</button>
        <h2 class="w-max p-3 mx-auto">Outline</h2>
        <button class="btn btn-outline btn-outline-default mb-2 mx-auto ml-4">Default</button>
        <button class="btn btn-outline btn-outline-primary mb-2 mx-auto ml-4">Primary</button>
        <button class="btn btn-outline btn-outline-success mb-2 mx-auto ml-4">Success</button>
        <button class="btn btn-outline btn-outline-info mb-2 mx-auto ml-4">Info</button>
        <button class="btn btn-outline btn-outline-warning mb-2 mx-auto ml-4">Warning</button>
        <button class="btn btn-outline btn-outline-danger mb-2 mx-auto ml-4">Danger</button>
        <button class="btn btn-outline btn-outline-dark mb-2 mx-auto ml-4">Dark</button>
        <h2 class="w-max p-3 mx-auto">Different Sizes</h2>
        <button class="btn btn-sm btn-primary mb-2 mx-auto ml-4">small</button>
        <button class="btn btn-success mb-2 mx-auto ml-4">default</button>
        <button class="btn btn-lg btn-secondary mb-2 mx-auto ml-4">large</button>
        <h2 class="w-max p-3 mx-auto">Svg Support</h2>
        <button class="btn btn-primary btn-active-primary mb-2 mx-auto ml-4">
            <SvgComponent name="fi-rr-alarm-clock" size="1.1rem" color="#ccc"/>
            Base
        </button>
        <button class="btn btn-light-success btn-light-active-success mb-2 mx-auto ml-4">
            <SvgComponent name="fi-rr-alarm-clock" size="1.1rem" color="#ccc"/>
            light
        </button>
        <button class="btn btn-outline btn-outline-info mb-2 mx-auto ml-4">
            <SvgComponent name="fi-rr-alarm-clock" size="1.1rem" color="#ccc"/>
            Outline
        </button>
        <h2 class="w-max p-3 mx-auto">Others</h2>
        <div class="flex justify-center gap-8">
            <div class="no-bg-svg-btn-blue">
                <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"/>
            </div>
            <div class="no-bg-svg-btn-red">
                <SvgComponent name="fi-rr-cross" size=".8rem" color="#F1416C"/>
            </div>
        </div>

        <br>

    </section>
</template>

<script>
import SvgComponent from '../Widgets/SvgComponent.vue'
export default {
    name: "Buttons",
    components: {SvgComponent}
}
</script>

<style scoped>

</style>
