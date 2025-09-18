import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useActivityLogsStore = defineStore('ActivityLogs', {
    state: () => {
        return {
            // constants start
            PageTitle: t('pages.ActivityLogs'),
            tableLayout: {
                gridTemplateColumns: '3rem 1fr 6rem 8.5rem 1fr 10.5rem 6rem',
                labels: [
                    t('fields.ID'),
                    t('fields.causer'),
                    t('fields.event'),
                    t('fields.subjectType'),
                    t('fields.subject'),
                    t('fields.createdAt'),
                    t('fields.actions'),
                ],
            },
            // constants end
            tableData: {},
            type: '',
            isSearching: false,
        }
    },
    actions: {
        setTableData(data){
            this.tableData = JSON.parse(JSON.stringify(data));
        },
        updateTableData(){
            axios.post(`/admin/${this.type}s/indexDataApi`)
                .then((res)=>{
                    this.tableData = res.data;
                })
        }
    },
})
