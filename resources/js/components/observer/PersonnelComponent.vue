<template>
    <div>
        <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                    <i class="fa fa-user-md"></i>&nbsp;&nbsp;Staff List
                </span>
            </div>
        </div>
        <!-- End Header -->

        <!-- Search Box -->
        <div class="row" style="margin-bottom: 10px">
            <div class="col-sm-6">
                <el-input
                    prefix-icon="el-icon-search"
                    v-model="search"
                    size="medium"
                    placeholder="Type to search"
                />
            </div>
        </div>
        <!-- Search End -->

        <!-- Card Begins Here -->
        <div class="card">
            <div class="card-body">
                <!-- Table -->
                <el-table v-loading="loading" :data="ListData">
                    <el-table-column
                        width="220"
                        label="Name"
                        prop="name"
                    ></el-table-column>
                    <el-table-column
                        width="160"
                        label="Employed As"
                        prop="is_private"
                    ></el-table-column>
                     <el-table-column
                        width="160"
                        label="Employment Type"
                        prop="is_parttime"
                    ></el-table-column>
                    <el-table-column
                        width="160"
                        label="Designation"
                        prop="designation"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        label="Sex"
                        prop="sex"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        label="Birthdate"
                        prop="birthdate"
                    ></el-table-column>
                    <el-table-column
                        min-width="150"
                        label="Hospital"
                        prop="hospital_code"
                    ></el-table-column>
                    <el-table-column width="60" align="right" fixed="right">
                        <template slot="header">
                            Action
                        </template>
                        <template slot-scope="scope">
                            <el-tooltip
                                class="item"
                                effect="light"
                                content="View"
                                placement="top"
                                :enterable=false
                            >
                                <el-button
                                    size="mini"
                                    type="info"
                                    icon="el-icon-info"
                                    circle
                                    @click="handleView(scope.$index, scope.row)"
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
                <!-- End table -->
            </div>
        </div>
        <!-- Card Ends Here -->

        <!-- Footer -->
        <hr />
        <div class="footer">
            <div class="containter-fluid">
                <div class="row text-center">
                    <span class="text-muted"
                        >&nbsp;&nbsp;&nbsp;&nbsp;©PF Management System
                        2020</span
                    >
                </div>
            </div>
        </div>
        <!-- Footer ends -->

        <!-- Show Personnel Details -->
        <el-dialog title="Staff Info" :visible.sync="dialogTableVisible">
            <el-table :data="gridData">
                <el-table-column
                    property="name"
                    label="Name"
                    width="210"
                ></el-table-column>
                <el-table-column
                    property="is_private"
                    label="Employed As"
                    width="120"
                ></el-table-column>
                <el-table-column
                    property="is_parttime"
                    label="Employment Type"
                    width="150"
                ></el-table-column>
                <el-table-column
                    property="designation"
                    label="Designatioon"
                    width="110"
                ></el-table-column>
                <el-table-column
                    property="sex"
                    label="Sex"
                    width="100"
                ></el-table-column>
                <el-table-column
                    property="birthdate"
                    label="Birthdate"
                    width="100"
                ></el-table-column>
                <el-table-column
                    property="hospital_code"
                    label="Hospital"
                    width="100"
                ></el-table-column>
            </el-table>
        </el-dialog>
        <!-- Show Personnel Details -->
    </div>
</template>
<script>
"use strict";
import constants from "../../constants.js";
export default {
    data() {
        return {
            page: 1,
            pageSize: 10,
            loading: true,
            search: "",
            data: [],
            dialogTableVisible: false,
            // View info data
            gridData: [
                {
                    name: "",
                    sex: "",
                    birthdate: "",
                    is_private: "",
                    is_parttime: "",
                    designation: "",
                    hospital_code: ""
                }
            ]
        }
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
                        .includes(this.search.toLowerCase()) ||
                    data.hospital_code
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
            );
        },
        ListData() {
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
        handleView(index, row) {
            this.dialogTableVisible = true;
            this.gridData[0].name = this.buildName(
                row.first_name,
                row.middle_name,
                row.last_name,
                row.name_suffix
            );
            this.gridData[0].is_private = row.is_private;
            this.gridData[0].is_parttime = row.is_parttime;
            this.gridData[0].designation = row.designation;
            this.gridData[0].sex = row.sex;
            this.gridData[0].birthdate = row.birthdate;
            this.gridData[0].hospital_code = row.hospital_code;
        },
        getPersonnelsList: function() {
            axios
                .get("observerPersonnelList")
                .then(response => {
                    response.data.forEach(element => {
                        this.buildPersonnelData(element);
                    });
                    this.data = response.data;
                    this.loading = false;
                })
                .catch(function(error) {});
        },
        assignType: function(type_value) {
            var type;
            switch (type_value) {
                case 0:
                    type = "Non-Private";
                    break;
                case 1:
                    type = "Private";
                    break;
                default:
                    type = "Not Known";
            }
            return type;
        },
        assignEmploymentType: function(employmentType_value) {
            var employmentType;
            switch (employmentType_value) {
                case 0:
                    employmentType = "Part-time";
                    break;
                case 1:
                    employmentType = "Full-time";
                    break;
                default:
                    employmentType = "Not Known";
            }
            return employmentType;
        },
        assignDesignation: function(designation_value) {
            var designation;
            switch (designation_value) {
                case 0:
                    designation = "Non-medical";
                    break;
                case 1:
                    designation = "Medical";
                    break;
                default:
                    designation = "Not Known";
            }
            return designation;
        },
        assignSex: function(sex_value) {
            var sex;
            switch (sex_value) {
                case 0:
                    sex = "Male";
                    break;
                case 1:
                    sex = "Female";
                    break;
                default:
                    sex = "Not Known";
            }
            return sex;
        },
        buildName: function(first_name, middle_name, last_name, suffix) {
            return (
                last_name +
                " " +
                suffix +
                ", " +
                first_name +
                " " +
                middle_name.slice(0, 1) +
                "."
            ).trim();
        },
        buildPersonnelData: function(element) {
            if (element.name_suffix == undefined) {
                element.name_suffix = "";
            }
            element.name = this.buildName(
                element.first_name,
                element.middle_name,
                element.last_name,
                element.name_suffix
            );
            element.sex = this.assignSex(element.sex);
            element.is_private = this.assignType(element.is_private);
            element.is_parttime = this.assignEmploymentType(element.is_parttime);
            element.designation = this.assignDesignation(element.designation);
        },
    },
    mounted() {
        this.getPersonnelsList();
    }


}
</script>