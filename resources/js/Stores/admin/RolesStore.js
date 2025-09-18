import { defineStore } from 'pinia'

import i18n from '../../i18n';
const t = i18n.global.t;

export const useRolesStore = defineStore('Roles', {
    state: () => {
        return {
            // constants start
            PageTitleIndex: t('pages.Roles'),
            PageTitleShow: t('pages.RolesDetails'),
            // constants end
            tableData: {},
            // Modal Start
            showCreateModal: false,
            openRoleSubId: 0,
            edit:{
                isEdit: false,
                toBeEdited: {},
            },
            allPermissions: [],
            // Modal end
            openedRowActionPanelId: 0, // for RoleShow component
            showFullScreenLoader: false,
        }
    },
    actions: {
        closeModal(){
            this.showCreateModal = false;
        },
        openModalCreate(){
            this.showCreateModal = true;
            this.edit.isEdit = false;
        },
        openModalEdit(item){
            this.showCreateModal = true;
            this.edit.isEdit = true;
            this.edit.toBeEdited = JSON.parse(JSON.stringify(item));
        },
        setTableData(data){
            this.tableData = JSON.parse(JSON.stringify(data));
        },
        openRowActionPanel(id){
            // for RoleShow component
            this.openedRowActionPanelId = id;
        },
        setOpenRoleSubId(id){
            if (this.openRoleSubId === id){
                this.openRoleSubId = 0;
            }else {
                this.openRoleSubId = id;
            }
        },
        updateTableData(){
            axios.get('/admin/roles/api/getTableData')
                .then((res)=>{
                    this.tableData = res.data;
                });
        }
    },
})
