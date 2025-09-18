import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useCommentsIndexStore = defineStore('CommentsIndex', {
    state: () => {
        return {
            // constants start
            PageTitle: t('pages.Comments'),
            tableLayout: {
                gridTemplateColumns: '3rem 3rem repeat(2,1fr) 5.5rem 1fr 6rem 6rem',
                labels: [
                    t('fields.ID'),
                    t('fields.sender'),
                    `${t('fields.product')}/${t('fields.article')}`,
                    t('fields.status'),
                    t('fields.text'),
                    t('fields.createdAt'),
                    t('fields.actions'),
                ],
            },
            // constants end
            showCreateModal: false,
            tableData: {},
            type: '',
            edit:{
                // isEdit: false, CommentsIndex only has editing
                toBeEdited: {},
            },
            allDataNames: [],
            // below is for selecting multiple data
            selectedIds: [],
            areAllSelected: false,
            isSearching: false,
            showFullScreenLoader: false,
        }
    },
    actions: {
        closeModal(){
            this.showCreateModal = false;
        },
        openModalEdit(item){
            this.showCreateModal = true;
            // this.edit.isEdit = true;
            this.edit.toBeEdited = JSON.parse(JSON.stringify(item));
        },
        setTableData(data){
            this.tableData = JSON.parse(JSON.stringify(data));
        },
        updateTableData(){
            axios.post(`/admin/${this.type}s/indexDataApi`)
                .then((res)=>{
                    this.tableData = res.data;
                })
        },
        selectAll(){
            if (this.tableData.data.length === this.selectedIds.length){
                this.selectedIds.splice(0, this.selectedIds.length);
            }else{
                // it empties the array
                this.selectedIds.splice(0, this.selectedIds.length);
                // then pushes all the values
                this.tableData.data.forEach( (item) => {
                    this.selectedIds.push(item.id);
                });
            }
        },
        checkIfAllAreSelected(){
            this.areAllSelected = this.selectedIds.length === this.tableData.data.length;
        }
    },
})
