<template>
    <div>
        <!-- Header -->
        <div class="row">
            <div class="col-sm-12">
                <h2 class="font-weight-bold">
                    <i class="fa fa-hospital-user"></i>&nbsp;&nbsp;Patients
                    List
                </h2>
            </div>
        </div>
        <hr />
        <!-- End Header -->

        <!-- Card Begins Here -->
        <div class="card">
            <div class="card-body">
                <!-- Table -->
                <el-table v-loading="loading" :data="ListData">
                    <el-table-column
                        width="120"
                        label="PhilHealth No."
                        prop="philhealth_number"
                    ></el-table-column>
                    <el-table-column
                        width="220"
                        label="Name"
                        prop="name"
                    ></el-table-column>
                    <el-table-column
                        width="100"
                        label="Sex"
                        prop="sex"
                    ></el-table-column>
                    <el-table-column
                        width="120"
                        label="Birthdate"
                        prop="birthdate"
                    ></el-table-column>
                    <el-table-column
                        width="180"
                        label="Marital Status"
                        prop="marital_status"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        label="Hospital"
                        prop="hospital_code"
                    ></el-table-column>
                    <el-table-column width="200" align="right" fixed="right">
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
        <!-- Card ends here -->

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

        <!-- Show Patient Details -->
        <el-dialog title="Patient Info" :visible.sync="dialogTableVisible">
            <el-table :data="gridData">
                <el-table-column
                    property="philhealth_number"
                    label="PhilHealth No."
                    width="120"
                ></el-table-column>
                <el-table-column
                    property="name"
                    label="Name"
                    width="220"
                ></el-table-column>
                <el-table-column
                    property="sex"
                    label="Sex"
                    width="100"
                ></el-table-column>
                <el-table-column
                    property="birthdate"
                    label="Birthdate"
                    width="120"
                ></el-table-column>
                <el-table-column
                    property="marital_status"
                    label="Marital Status"
                    width="180"
                ></el-table-column>
                <el-table-column
                    property="hospital_code"
                    label="Hospital"
                    width="150"
                ></el-table-column>
            </el-table>
        </el-dialog>
        <!-- Show Patient Details -->

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
             // Show info data
            gridData: [
                {
                    philhealth_number: "",
                    name: "",
                    sex: "",
                    birthdate: "",
                    marital_status: "",
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
                    data.philhealth_number
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
            this.gridData[0].philhealth_number = row.philhealth_number;
            this.gridData[0].name = this.buildName(
                row.first_name,
                row.middle_name,
                row.last_name,
                row.name_suffix
            );
            this.gridData[0].sex = row.sex;
            this.gridData[0].birthdate = row.birthdate;
            this.gridData[0].marital_status = row.marital_status;
            this.gridData[0].hospital_code = row.hospital_code;
        },
        getPatientsList: function() {
            axios
                .get("observerPatientList")
                .then(response => {
                    response.data.forEach(element => {
                        this.buildPatientData(element);
                    });
                    this.data = response.data;
                    this.loading = false;
                })
                .catch(function(error) {});
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
        assignSex: function(sex_value) {
            var sex;
            switch (sex_value) {
                case 0:
                    sex = "Male";
                    break;
                case 1:
                    sex = "Female";
                    break;
                case 9:
                    sex = "Not Applicable";
                    break;
                default:
                    sex = "Not Known";
            }
            return sex;
        },
        assignMaritalStatus: function(marital_status_value) {
            var marital_status;
            switch (marital_status_value) {
                case 0:
                    marital_status = "Single";
                    break;
                case 1:
                    marital_status = "Married";
                    break;
                case 2:
                    marital_status = "Divorced";
                    break;
                case 3:
                    marital_status = "Widowed";
                    break;
                default:
                    marital_status = "Others/Prefer Not to Say";
            }
            return marital_status;
        },
        buildPatientData: function(element) {
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
            element.marital_status = this.assignMaritalStatus(
                element.marital_status
            );
        },
    },
    mounted() {
        this.getPatientsList();
    }

}
</script>