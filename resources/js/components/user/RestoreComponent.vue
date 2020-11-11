<template>
    <div>
       
       <!-- Header -->
        <div class="row header-top"> 
            <div class="header-title-parent" style="padding-top:2px !important;padding-bottom:2px !important;">
                <span class="header-title">
                <i class="fa fa-file-medical-alt"></i>&nbsp;&nbsp;Restore List
                </span>
            </div>
        </div>
        <!-- End Header -->


        <div class="card">
            <div class="card-body">
                <el-table v-loading="loading" :data="listData">
                    <el-table-column
                        width="150"
                        label="Philhealth No."
                        prop="philhealth"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        label="Patient"
                        prop="pfname"
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
                    <el-table-column
                        width="150"
                        label="Diagnois"
                        prop="final_diagnosis"
                    ></el-table-column>

                    <el-table-column width="280" align="right" fixed="right">
                        <template slot="header" slot-scope="scope">
                            <el-input
                                v-model="search"
                                size="mini"
                                placeholder="Type to search"
                            />
                        </template>
                        <template slot-scope="scope">
                            <el-tooltip
                                class="item"
                                effect="light"
                                content="View"
                                placement="top"
                                :enterable = false
                            >
                                <el-button
                                    size="mini"
                                    type="info"
                                    icon="el-icon-info"
                                    circle
                                    @click="handleView(scope.$index, scope.row)"
                                ></el-button>
                            </el-tooltip>
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
        <!-- Show Patient Details -->
        <el-dialog title="Personnel Details" :visible.sync="dialogTableVisible">
            <el-table :data="gridData">
                <el-table-column
                    property="psfname"
                    label="Firstname"
                    width="200"
                ></el-table-column>
                <el-table-column
                    property="psmname"
                    label="Middlename"
                    width="200"
                ></el-table-column>
                <el-table-column
                    property="pslname"
                    label="Lastname"
                    width="formLabelWidth"
                ></el-table-column>
            </el-table>
        </el-dialog>
        <!-- Show Patient Details -->
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
            dialogTableVisible: false,
            gridData: [
                {
                    psfname: "",
                    psmname: "",
                    pslname: ""
                }
            ],
            actionCol: {
                label: "Actions",
                props: {
                    align: "center"
                },
                buttons: [
                    {
                        props: {
                            type: "info",
                            icon: "el-icon-info",
                            circle: true,
                            size: "mini"
                        },
                        handler: row => {
                            this.dialogTableVisible = true;
                            this.gridData[0].psfname = row.psfname;
                            this.gridData[0].psmname = row.psmname;
                            this.gridData[0].pslname = row.pslname;
                        }
                    },
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
                    data.first_name
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.last_name
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
        formLoading: function() {
            const loading = this.$loading({
                lock: true,
                spinner: "el-icon-loading",
                target: "div.el-dialog"
            });
            loading.close();
        },
        handleView(index, row) {
            this.dialogTableVisible = true;
            this.gridData[0].psfname = row.psfname;
            this.gridData[0].psmname = row.psmname;
            this.gridData[0].pslname = row.pslname;
        },
        handleRestore(index, row) {
           this.editRestore(row.id);
        },
        open_notif: function(status, title, message) {
            if (status == "success") {
                this.$notify.success({
                    title: title,
                    message: message,
                    offset: 0
                });
            } else if (status == "error") {
                this.$notify.error({
                    title: title,
                    message: message,
                    offset: 0
                });
            } else if (status == "info") {
                this.$notify.info({
                    title: title,
                    message: message,
                    offset: 0
                });
            } else if (status == "warning") {
                this.$notify.warning({
                    title: title,
                    message: message,
                    offset: 0
                });
            }
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
                            _this.open_notif(
                                "success",
                                "Restore",
                                "Successfully"
                            );
                            _this.getRestore();
                        }
                    });
                })
                .catch(action => {
                    this.$message({
                        type: "success",
                        message: action === "cancel" ? "Canceled" : "No changes"
                    });
                });
        },
        getRestore: function() {
            axios
                .get("restore_get")
                .then(response => {
                    response.data.forEach(entry => {
                        if (entry.pnamesuffix == null) {
                            entry.pfname =
                                entry.plname +
                                ", " +
                                entry.pfname +
                                " " +
                                entry.pmname.slice(0, 1) +
                                ".";
                        } else {
                            entry.pfname =
                                entry.plname +
                                " " +
                                entry.pnamesuffix +
                                ", " +
                                entry.pfname +
                                " " +
                                entry.pmname.slice(0, 1) +
                                ".";
                        }
                    });
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
