<script setup>
import HeaderPanel from "../Pages/Admin/Header/HeaderPanel.vue";
import SideBarPanel from "../Pages/Admin/SideBar/SideBarPanel.vue";
import AdminSvgs from "../Components/admin/AdminSvgs.vue";
</script>

<template>
    <admin-svgs></admin-svgs>
    <header-panel></header-panel>
    <side-bar-panel></side-bar-panel>
    <div class="content pt-[5rem] ps-[17.2rem] pe-3">
        <slot></slot>
    </div>
</template>
