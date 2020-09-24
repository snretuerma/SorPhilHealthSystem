<template>
    <div>
        <data-tables
            :data="data"
            :page-size="1"
            :pagination-props="{ pageSizes: [10, 20, 50] }">
            <div slot="empty">Table Empty</div>
            <el-table-column
                v-for="title in titles"
                :prop="title.prop"
                :label="title.label"
                :key="title.label"
                sortable="custom">
            </el-table-column>
            <p slot="append"></p>
        </data-tables>
    </div>
</template>

<script>
    export default {
        data() {
            return{
                data: [],
                titles: [
                    {
                        prop: 'first_name',
                        label: 'First Name'
                    },
                    {
                        prop: 'middle_name',
                        label: 'Middle Name'
                    },
                    {
                        prop: 'last_name',
                        label: 'Last Name'
                    },
                    {
                        prop: 'name_suffix',
                        label: 'Suffix'
                    },
                    {
                        prop: 'sex',
                        label: 'Sex'
                    },
                    {
                        prop: 'birthdate',
                        label: 'Birthdate'
                    },
                    {
                        prop: 'marital_status',
                        label: 'Marital Status'
                    },
                    {
                        prop: 'philhealth_number',
                        label: 'PhilHealth #'
                    },
                ],
                layout: 'pagination, table'
            }
        },
        methods: {
            getPatients : function () {
                axios.get('patients_get')
                .then(response => {
                    response.data.forEach(element => {
                        if(element.name_suffix == undefined) {
                            element.name_suffix = 'None'
                        }
                        switch(element.sex) {
                            case 1:
                                element.sex = "Male";
                                break;
                            case 2:
                                element.sex = "Female";
                                break;
                            case 9:
                                element.sex = "Not Applicable";
                                break;
                            default:
                                element.sex = "Not Known";
                        }
                        switch(element.marital_status) {
                            case 0:
                                element.marital_status = "Single";

                                break;
                            case 1:
                                element.marital_status = "Married";
                                break;
                            case 2:
                                element.marital_status = "Divorced";
                                break;
                            case 2:
                                element.marital_status = "Widowed";
                                break;
                            default:
                                element.marital_status = "Others/Prefer Not to Say";
                        }
                    });
                    this.data = response.data;
                    
                })
                .catch(function (error) {
                     
                });
            },
        },
        mounted() {
            this.getPatients();
        }
    }
</script>
