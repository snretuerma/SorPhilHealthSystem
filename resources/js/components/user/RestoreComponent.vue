<template>
    <div>

        <div class="row header-top">
            <div class="header-title-parent" style="padding-top:2px !important;padding-bottom:2px !important;">
                <span class="header-title">
                <i class="fa fa-history"></i>&nbsp;&nbsp;Restore List
                </span>
            </div>
        </div>

        <div class="row" style="margin-bottom: 10px">
            <div class="col-5">
                <el-input
                    prefix-icon="el-icon-search"
                    v-model="search"
                    size="medium"
                    placeholder="Type to search"
                />
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <el-table v-loading="loading" :data="listData">
                    <el-table-column
                        min-width="200"
                        label="Patient"
                        prop="patient_name"
                    ></el-table-column>
                    <el-table-column
                        min-width="180"
                        label="Batch"
                        prop="batch"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        label="Record Type"
                        prop="record_type"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        label="PF"
                        prop="total"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        label="Admission Date"
                        prop="admission_date"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        label="Discharge Date"
                        prop="discharge_date"
                    ></el-table-column>
                    <el-table-column width="135" align="center" fixed="right">
                        <template slot="header">
                            Action
                        </template>
                        <template slot-scope="scope">
                            <el-tooltip
                                class="item"
                                effect="light"
                                content="Restore"
                                placement="top"
                                :enterable = false
                            >
                                <el-button
                                    size="mini"
                                    type="success"
                                    icon="el-icon-check"
                                    circle
                                    @click="
                                        handleRestore(scope.$index, scope.row)
                                    "
                                ></el-button>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                </el-table>
                <div style="text-align: center">
                    <el-pagination
                        background
                        layout="prev, pager, next"
                        @current-change="handleCurrentChange"
                        :page-size="pageSize"
                        :total="total"
                    >
                    </el-pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
"use strict";
export default {
    data() {
        return {
            data: [],
            page: 1,
            pageSize: 10,
            loading: true,
            search: "",
            actionCol: {
                label: "Actions",
                props: {
                    align: "center"
                },
                buttons: [
                    {
                        props: {
                            type: "success",
                            icon: "el-icon-check",
                            circle: true,
                            size: "mini"
                        },
                        handler: row => {
                            this.editRestore(row.id);
                        }
                    }
                ]
            },
        };
    },
    computed: {
        searching() {
            if (!this.search) {
                this.total = this.data.length;
                return this.data;
            }
            this.page = 1;
            return this.data.filter(
                data =>
                    data.patient_name
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.batch
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.record_type
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
            );
        },
        listData() {
            this.total = this.searching.length;
            return this.searching.slice(
                this.pageSize * this.page - this.pageSize,
                this.pageSize * this.page
            );
        }
    },
    methods: {
        handleCurrentChange(val) {
            this.page = val;
        },
        handleRestore(index, row) {
           this.editRestore(row.id);
        },
        editRestore: function(id) {
            this.$confirm(
                "Are you sure you want to restore?",
                "Confirm Restore",
                {
                    distinguishCancelAndClose: true,
                    confirmButtonText: "Restore",
                    cancelButtonText: "Cancel",
                    type: "primary"
                }
            )
                .then(() => {
                    var _this = this;
                    axios.post("edit_restore/" + id).then(function(response) {
                        if (response.status > 199 && response.status < 203) {
                            _this.$notify({
                                type: 'success',
                                title: 'Restore',
                                message: "Record successfully restored",
                            });
                            _this.getRestore();
                        }
                    });
                })
                .catch(action => {
                    this.$notify({
                        type: 'info',
                        title: 'Cancelled',
                        message: "No changes",
                    });
                });
        },
        getRestore: function() {
            axios
            .get("restore_get")
            .then(response => {
                this.data = response.data;
                this.loading = false;
            })
            .catch(function(error) {});
        }
    },
    mounted() {
        this.getRestore();
    }
};
</script>
